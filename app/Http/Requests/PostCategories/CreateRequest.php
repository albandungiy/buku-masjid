<?php

namespace App\Http\Requests\PostCategories;

use App\Models\PostCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', new PostCategory);
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
     * Save post category to the database.
     *
     * @return \App\Models\PostCategory
     */
    public function save()
    {
        $newCategory = $this->validated();
        $newCategory['slug'] = $this->resolveSlug($newCategory['name']);
        $newCategory['creator_id'] = $this->user()->id;

        return PostCategory::create($newCategory);
    }

    private function resolveSlug(string $name): string
    {
        $base = Str::slug($name);
        $unique = $base;
        $suffix = 1;
        while (PostCategory::where('slug', $unique)->exists()) {
            $unique = $base.'-'.(++$suffix);
        }

        return $unique;
    }
}
