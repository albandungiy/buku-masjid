<?php

namespace App\Http\Requests\Events;

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
        return $this->user()->can('update', $this->route('event'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'book_id' => 'nullable|exists:books,id',
            'title' => 'required|max:60',
            'description' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'color_code' => 'nullable|string|max:20',
        ];
    }

    /**
     * Update event in database.
     *
     * @return \App\Models\Event
     */
    public function save()
    {
        $event = $this->route('event');
        $event->update($this->validated());

        return $event;
    }
}
