<?php

namespace App\Http\Requests\Events;

use App\Jobs\Files\OptimizeImage;
use App\Models\Event;
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
        return $this->user()->can('create', new Event);
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
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:jpg,bmp,png,avif,webp,pdf', 'max:5120'],
        ];
    }

    /**
     * Save event to the database.
     *
     * @return \App\Models\Event
     */
    public function save()
    {
        $newEvent = $this->validated();
        $newEvent['creator_id'] = $this->user()->id;
        $event = Event::create($newEvent);

        if (!isset($newEvent['files'])) {
            return $event;
        }

        $filePath = 'files/'.now()->format('Y/m/d');
        $imageExtensions = ['jpg', 'jpeg', 'bmp', 'png', 'avif', 'webp'];
        foreach ($newEvent['files'] as $uploadedFile) {
            $fileName = $uploadedFile->store($filePath);
            $isImage = in_array(strtolower($uploadedFile->getClientOriginalExtension()), $imageExtensions);
            $file = $event->files()->create([
                'type_code' => $isImage ? 'raw_image' : 'document',
                'file_path' => $fileName,
            ]);

            if ($isImage) {
                dispatch(new OptimizeImage($file));
            }
        }

        return $event;
    }
}
