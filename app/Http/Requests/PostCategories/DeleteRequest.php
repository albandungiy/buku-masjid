<?php

namespace App\Http\Requests\PostCategories;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('delete', $this->route('post_category'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->route('post_category')->posts()->exists()) {
                $validator->errors()->add('post_category_id', __('validation.post_category.has_posts'));
            }
        });
    }

    /**
     * Delete post category from database.
     *
     * @return bool
     */
    public function delete()
    {
        return $this->route('post_category')->delete();
    }
}
