<?php

namespace App\Http\Requests\Distributions;

use App\Models\Distribution;
use App\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class ApproveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $distribution = $this->route('distribution');

        return $this->user()->can('approve', $distribution)
        && $this->user()->can('manage-distributions', $distribution->book);
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
     * Re-validate the fund balance at approval time — additional pending distributions may
     * have been created since this one was submitted, so the check in CreateRequest alone
     * is not enough to prevent overdrawing the book.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $distribution = $this->route('distribution');
            if ($distribution->amount > $distribution->book->getBalance()) {
                $validator->errors()->add('amount', __('validation.distribution.amount.not_exceed_balance', [
                    'balance' => format_number($distribution->book->getBalance()),
                ]));
            }
        });
    }

    /**
     * Approve the distribution and record it as a spending Transaction in its fund book.
     *
     * @return \App\Models\Distribution
     */
    public function save()
    {
        $distribution = $this->route('distribution');

        DB::transaction(function () use ($distribution) {
            $transaction = Transaction::create([
                'date' => $distribution->distribution_date,
                'amount' => $distribution->amount,
                'in_out' => Transaction::TYPE_SPENDING,
                'description' => $distribution->title,
                'category_id' => $distribution->category_id,
                'book_id' => $distribution->book_id,
                'creator_id' => $this->user()->id,
            ]);

            $distribution->update([
                'status_id' => Distribution::STATUS_APPROVED,
                'transaction_id' => $transaction->id,
                'approved_id' => $this->user()->id,
                'approved_at' => now(),
            ]);
        });

        return $distribution->fresh();
    }
}
