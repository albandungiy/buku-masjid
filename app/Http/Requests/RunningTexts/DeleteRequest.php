<?php

namespace App\Http\Requests\RunningTexts;

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
        return $this->user()->can('delete', $this->route('running_text'));
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
     * Delete running text from database.
     *
     * @return bool
     */
    public function delete()
    {
        return $this->route('running_text')->delete();
    }
}
