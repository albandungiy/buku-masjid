<?php

namespace App\Http\Requests\RunningTexts;

use App\Models\RunningText;
use Illuminate\Foundation\Http\FormRequest;

class ReorderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('reorder', RunningText::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ordered_ids' => ['required', 'array'],
            'ordered_ids.*' => ['integer', 'distinct', 'exists:running_texts,id'],
        ];
    }

    /**
     * Persist the new order (index in the array = new `order` value).
     *
     * @return bool
     */
    public function save()
    {
        foreach ($this->validated()['ordered_ids'] as $index => $id) {
            RunningText::whereKey($id)->update(['order' => $index]);
        }

        return true;
    }
}
