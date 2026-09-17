<?php

namespace App\Http\Requests\Distributions;

use App\Jobs\Files\OptimizeImage;
use App\Models\Book;
use App\Models\Distribution;
use App\Rules\Distributions\NotExceedBalance;
use Illuminate\Foundation\Http\FormRequest;
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
        return $this->user()->can('create', new Distribution)
        && $this->user()->can('manage-distributions', auth()->activeBook());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $book = Book::find($this->input('book_id'));

        return [
            // The Hak Amil book is a derived pool, not a fund distributed directly to Asnaf.
            'book_id' => ['required', 'exists:books,id', Rule::notIn(array_filter([config('ziswaf.hak_amil_book_id')]))],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(fn ($query) => $query->where('book_id', $this->input('book_id'))),
            ],
            'title' => 'required|max:60',
            'amount' => ['required', 'numeric', 'min:1', new NotExceedBalance($book)],
            'asnaf_detail' => 'nullable|array',
            'description' => 'nullable|string|max:255',
            'distribution_date' => 'required|date|date_format:Y-m-d',
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:jpg,bmp,png,avif,webp,pdf', 'max:5120'],
        ];
    }

    /**
     * Save distribution to the database, as a pending record awaiting approval.
     *
     * @return \App\Models\Distribution
     */
    public function save()
    {
        $newDistribution = $this->validated();
        $newDistribution['creator_id'] = $this->user()->id;
        $newDistribution['status_id'] = Distribution::STATUS_PENDING;
        $distribution = Distribution::create($newDistribution);

        if (!isset($newDistribution['files'])) {
            return $distribution;
        }

        $filePath = 'files/'.now()->format('Y/m/d');
        $imageExtensions = ['jpg', 'jpeg', 'bmp', 'png', 'avif', 'webp'];
        foreach ($newDistribution['files'] as $uploadedFile) {
            $fileName = $uploadedFile->store($filePath);
            $isImage = in_array(strtolower($uploadedFile->getClientOriginalExtension()), $imageExtensions);
            $file = $distribution->files()->create([
                'type_code' => $isImage ? 'raw_image' : 'document',
                'file_path' => $fileName,
            ]);

            // Non-image uploads (e.g. a PDF distribution report) are stored as-is —
            // OptimizeImage only knows how to resize actual images.
            if ($isImage) {
                dispatch(new OptimizeImage($file));
            }
        }

        return $distribution;
    }
}
