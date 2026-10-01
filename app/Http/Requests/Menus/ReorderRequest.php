<?php

namespace App\Http\Requests\Menus;

use App\Models\Menu;
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
        return $this->user()->can('reorder', Menu::class);
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
            'ordered_ids.*' => ['integer', 'distinct', 'exists:menus,id'],
        ];
    }

    /**
     * Persist the new order (index in the array = new `order` value) for every menu
     * given — used by the admin drag-and-drop reorder UI (Fase 5).
     *
     * @return bool
     */
    public function save()
    {
        foreach ($this->validated()['ordered_ids'] as $index => $id) {
            Menu::whereKey($id)->update(['order' => $index]);
        }

        return true;
    }
}
