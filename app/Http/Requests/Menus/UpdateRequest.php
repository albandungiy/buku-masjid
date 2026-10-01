<?php

namespace App\Http\Requests\Menus;

use App\Models\Menu;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
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
        return $this->user()->can('update', $this->route('menu'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $menu = $this->route('menu');

        return [
            // A menu can't be its own parent — deeper cycle detection isn't needed since
            // the app only ever nests one level (see Menu::children()).
            'parent_id' => ['nullable', 'exists:menus,id', Rule::notIn([$menu->id])],
            'label' => ['required', 'string', 'max:60'],
            'target_type' => ['required', Rule::in(config('cms.menu_target_types'))],
            'target_value' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $targetType = $this->input('target_type');
            $targetValue = $this->input('target_value');

            if ($targetType == Menu::TARGET_ROUTE && !Route::has($targetValue)) {
                $validator->errors()->add('target_value', __('validation.menu.target_value.route_not_found'));
            }
            if ($targetType == Menu::TARGET_POST && !Post::whereKey($targetValue)->exists()) {
                $validator->errors()->add('target_value', __('validation.menu.target_value.post_not_found'));
            }
            if ($targetType == Menu::TARGET_URL && $targetValue && !preg_match('#^(/|https?://)#', $targetValue)) {
                $validator->errors()->add('target_value', __('validation.menu.target_value.url_invalid'));
            }
        });
    }

    /**
     * Update menu in database.
     *
     * @return \App\Models\Menu
     */
    public function save()
    {
        $menu = $this->route('menu');
        $newMenu = $this->validated();
        $newMenu['is_active'] = $this->boolean('is_active');
        $menu->update($newMenu);

        return $menu;
    }
}
