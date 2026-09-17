<?php

namespace App\Http\Requests\Donations;

use App\Models\Book;
use App\Models\Donation;
use App\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Setting;

class ConfirmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $donation = $this->route('donation');

        return $this->user()->can('confirm', $donation)
        && $this->user()->can('manage-donations', $donation->book);
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
     * Confirm the donation: split it into a net-amount Transaction (fund book) and a
     * Hak Amil-amount Transaction (Hak Amil book), based on the fund book's configured
     * Hak Amil percentage (see Setting::for($book) usage below).
     *
     * @return \App\Models\Donation
     */
    public function save()
    {
        $donation = $this->route('donation');
        $hakAmilBook = Book::findOrFail(config('ziswaf.hak_amil_book_id'));
        $percentage = (float) Setting::for($donation->book)->get('hak_amil_percentage', 0);

        $hakAmilAmount = round($donation->amount * $percentage / 100, 2);
        $netAmount = $donation->amount - $hakAmilAmount;

        DB::transaction(function () use ($donation, $hakAmilBook, $hakAmilAmount, $netAmount) {
            $netTransaction = Transaction::create([
                'date' => $donation->date,
                'amount' => $netAmount,
                'in_out' => Transaction::TYPE_INCOME,
                'description' => __('donation.net_transaction_description', ['id' => $donation->id]),
                'partner_id' => $donation->partner_id,
                'book_id' => $donation->book_id,
                'creator_id' => $this->user()->id,
            ]);

            $hakAmilTransaction = null;
            if ($hakAmilAmount > 0) {
                $hakAmilTransaction = Transaction::create([
                    'date' => $donation->date,
                    'amount' => $hakAmilAmount,
                    'in_out' => Transaction::TYPE_INCOME,
                    'description' => __('donation.hak_amil_transaction_description', ['id' => $donation->id]),
                    'partner_id' => $donation->partner_id,
                    'book_id' => $hakAmilBook->id,
                    'creator_id' => $this->user()->id,
                ]);
            }

            $donation->update([
                'status_id' => Donation::STATUS_CONFIRMED,
                'net_transaction_id' => $netTransaction->id,
                'hak_amil_transaction_id' => $hakAmilTransaction?->id,
                'confirmed_at' => now(),
            ]);
        });

        return $donation->fresh();
    }
}
