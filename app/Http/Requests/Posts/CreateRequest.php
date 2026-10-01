<?php

namespace App\Http\Requests\Posts;

use App\Jobs\Files\OptimizeImage;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', new Post);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_code' => ['required', Rule::in([Post::TYPE_PAGE, Post::TYPE_NEWS])],
            'category_id' => ['nullable', 'exists:post_categories,id'],
            'title' => ['required', 'string', 'max:150'],
            // Left blank, the slug is auto-generated from the title in save(); typed in
            // manually it must be unique right away, rather than silently deduped.
            'slug' => ['nullable', 'string', 'max:180', 'alpha_dash', 'unique:posts,slug'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'status_id' => ['required', Rule::in(Post::getConstants('STATUS'))],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:jpg,bmp,png,avif,webp', 'max:5120'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('type_code') == Post::TYPE_PAGE && $this->filled('category_id')) {
                $validator->errors()->add('category_id', __('validation.post.category_id.page_no_category'));
            }
        });
    }

    /**
     * Save post to the database.
     *
     * @return \App\Models\Post
     */
    public function save()
    {
        $newPost = $this->validated();
        $newPost['slug'] = $this->resolveSlug($newPost['slug'] ?? null, $newPost['title']);
        $newPost['creator_id'] = $this->user()->id;
        if ($newPost['type_code'] == Post::TYPE_PAGE) {
            $newPost['category_id'] = null;
        }
        if ($newPost['status_id'] == Post::STATUS_PUBLISHED) {
            $newPost['published_at'] = now();
        }

        $post = Post::create($newPost);

        if (!isset($newPost['files'])) {
            return $post;
        }

        $filePath = 'files/'.now()->format('Y/m/d');
        $imageExtensions = ['jpg', 'jpeg', 'bmp', 'png', 'avif', 'webp'];
        foreach ($newPost['files'] as $uploadedFile) {
            $fileName = $uploadedFile->store($filePath);
            $isImage = in_array(strtolower($uploadedFile->getClientOriginalExtension()), $imageExtensions);
            $file = $post->files()->create([
                'type_code' => $isImage ? 'raw_image' : 'document',
                'file_path' => $fileName,
            ]);

            if ($isImage) {
                dispatch(new OptimizeImage($file));
            }
        }

        return $post;
    }

    private function resolveSlug(?string $slug, string $title): string
    {
        if ($slug) {
            return $slug;
        }

        $base = Str::slug($title);
        $unique = $base;
        $suffix = 1;
        while (Post::where('slug', $unique)->exists()) {
            $unique = $base.'-'.(++$suffix);
        }

        return $unique;
    }
}
