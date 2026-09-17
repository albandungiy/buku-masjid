<?php

namespace App\Http\Controllers\Donations;

use App\Http\Controllers\Controller;
use App\Http\Requests\Donations\ConfirmRequest;
use App\Models\Donation;

class ConfirmController extends Controller
{
    // The unused $donation parameter is required so Laravel performs implicit route
    // model binding on {donation} — without an Eloquent-typed parameter somewhere in
    // this action's signature, $this->route('donation') inside ConfirmRequest stays a
    // raw route-parameter string instead of a Donation model, and every policy check
    // in ConfirmRequest::authorize() silently fails closed (403), regardless of role.
    public function update(ConfirmRequest $donationConfirmForm, Donation $donation)
    {
        $donation = $donationConfirmForm->save();
        flash(__('donation.confirmed'), 'success');

        return redirect()->route('donations.show', $donation);
    }
}
