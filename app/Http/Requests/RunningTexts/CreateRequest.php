<?php

namespace App\Http\Requests\RunningTexts;

use App\Models\RunningText;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', new RunningText);
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
        ];
    }

    /**
     * Save running text to the database.
     *
     * @return \App\Models\RunningText
     */
    public function save()
    {
        $newRunningText = $this->validated();
        $newRunningText['order'] = (int) RunningText::max('order') + 1;
        $newRunningText['creator_id'] = $this->user()->id;

        return RunningText::create($newRunningText);
    }
}
