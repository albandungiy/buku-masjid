<?php

namespace App\Http\Requests\RunningTexts;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('running_text'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Update running text in database.
     *
     * @return \App\Models\RunningText
     */
    public function save()
    {
        $runningText = $this->route('running_text');
        $newRunningText = $this->validated();
        $newRunningText['is_active'] = $this->boolean('is_active');
        $runningText->update($newRunningText);

        return $runningText;
    }
}
