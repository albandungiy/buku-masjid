<?php

namespace App\Http\Requests\PostCategories;

use App\Models\PostCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('post_category'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:60'],
        ];
    }

    /**
     * Update post category in database.
     *
     * @return \App\Models\PostCategory
     */
    public function save()
    {
        $category = $this->route('post_category');
        $newCategory = $this->validated();
        if ($newCategory['name'] != $category->name) {
            $newCategory['slug'] = $this->resolveSlug($category, $newCategory['name']);
        }
        $category->update($newCategory);

        return $category;
    }

    private function resolveSlug(PostCategory $category, string $name): string
    {
        $base = Str::slug($name);
        $unique = $base;
        $suffix = 1;
        while (PostCategory::where('slug', $unique)->where('id', '!=', $category->id)->exists()) {
            $unique = $base.'-'.(++$suffix);
        }

        return $unique;
    }
}
