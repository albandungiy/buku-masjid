<?php

namespace App\Http\Requests\Distributions;

use App\Models\Distribution;
use Illuminate\Foundation\Http\FormRequest;

class RejectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $distribution = $this->route('distribution');

        return $this->user()->can('reject', $distribution)
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
     * Reject the distribution. No Transaction is created — the fund stays untouched.
     *
     * @return \App\Models\Distribution
     */
    public function save()
    {
        $distribution = $this->route('distribution');
        $distribution->update(['status_id' => Distribution::STATUS_REJECTED]);

        return $distribution;
    }
}
