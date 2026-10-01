<?php

namespace App\Http\Requests\Posts;

use App\Jobs\Files\OptimizeImage;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('post'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $post = $this->route('post');

        return [
            'category_id' => ['nullable', 'exists:post_categories,id'],
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', 'alpha_dash', Rule::unique('posts', 'slug')->ignore($post->id)],
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
            $post = $this->route('post');
            if ($post->type_code == Post::TYPE_PAGE && $this->filled('category_id')) {
                $validator->errors()->add('category_id', __('validation.post.category_id.page_no_category'));
            }
        });
    }

    /**
     * Update post in database.
     *
     * @return \App\Models\Post
     */
    public function save()
    {
        $post = $this->route('post');
        $newPost = $this->validated();

        // Reserved homepage-section pages (config('cms.reserved_page_slugs')) can never
        // change slug — silently keep the existing one instead of rejecting the whole
        // update, since the rest of the form (title, content) is still fully editable.
        if ($post->isReservedPage()) {
            unset($newPost['slug']);
        } elseif (empty($newPost['slug'])) {
            $newPost['slug'] = $this->resolveSlug($post, $newPost['title']);
        }

        if ($post->type_code == Post::TYPE_PAGE) {
            $newPost['category_id'] = null;
        }
        if ($newPost['status_id'] == Post::STATUS_PUBLISHED && $post->status_id != Post::STATUS_PUBLISHED) {
            $newPost['published_at'] = now();
        }

        $post->update($newPost);

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

    private function resolveSlug(Post $post, string $title): string
    {
        $base = Str::slug($title);
        $unique = $base;
        $suffix = 1;
        while (Post::where('slug', $unique)->where('id', '!=', $post->id)->exists()) {
            $unique = $base.'-'.(++$suffix);
        }

        return $unique;
    }
}
