<?php

namespace App\Http\Requests\Donations;

use App\Jobs\Files\OptimizeImage;
use App\Models\Donation;
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
        return $this->user()->can('create', new Donation)
        && $this->user()->can('manage-donations', auth()->activeBook());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'partner_id' => 'nullable|exists:partners,id',
            // The Hak Amil book is a derived pool, not a fund a muzakki donates into directly.
            'book_id' => ['required', 'exists:books,id', Rule::notIn(array_filter([config('ziswaf.hak_amil_book_id')]))],
            'date' => 'required|date|date_format:Y-m-d',
            'amount' => 'required|numeric|min:1',
            'payment_method_code' => ['required', Rule::in(array_keys(config('ziswaf.payment_methods')))],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'mimes:jpg,bmp,png,avif,webp', 'max:5120'],
        ];
    }

    /**
     * Save donation to the database, as a pending record awaiting confirmation.
     *
     * @return \App\Models\Donation
     */
    public function save()
    {
        $newDonation = $this->validated();
        $newDonation['creator_id'] = $this->user()->id;
        $newDonation['status_id'] = Donation::STATUS_PENDING;
        $donation = Donation::create($newDonation);

        if (!isset($newDonation['files'])) {
            return $donation;
        }

        $filePath = 'files/'.now()->format('Y/m/d');
        foreach ($newDonation['files'] as $uploadedFile) {
            $fileName = $uploadedFile->store($filePath);
            $file = $donation->files()->create([
                'type_code' => 'raw_image',
                'file_path' => $fileName,
            ]);
            dispatch(new OptimizeImage($file));
        }

        return $donation;
    }
}
