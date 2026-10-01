<?php

namespace App\Http\Requests\Menus;

use App\Models\Menu;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
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
        return $this->user()->can('create', new Menu);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'parent_id' => ['nullable', 'exists:menus,id'],
            'label' => ['required', 'string', 'max:60'],
            'target_type' => ['required', Rule::in(config('cms.menu_target_types'))],
            'target_value' => ['required', 'string', 'max:255'],
            'order' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateTarget($validator);
        });
    }

    private function validateTarget($validator)
    {
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
    }

    /**
     * Save menu to the database.
     *
     * @return \App\Models\Menu
     */
    public function save()
    {
        $newMenu = $this->validated();
        $newMenu['location_code'] = config('cms.default_menu_location');
        $newMenu['order'] = $newMenu['order'] ?? ((int) Menu::max('order') + 1);
        $newMenu['is_active'] = true;
        $newMenu['creator_id'] = $this->user()->id;

        return Menu::create($newMenu);
    }
}
