<?php

namespace App\Http\Controllers\Distributions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Distributions\ApproveRequest;
use App\Models\Distribution;

class ApproveController extends Controller
{
    // The unused $distribution parameter is required so Laravel performs implicit route
    // model binding on {distribution} — without an Eloquent-typed parameter somewhere in
    // this action's signature, $this->route('distribution') inside ApproveRequest stays
    // a raw route-parameter string instead of a Distribution model, and every policy
    // check in ApproveRequest::authorize() silently fails closed (403), regardless of role.
    public function update(ApproveRequest $distributionApproveForm, Distribution $distribution)
    {
        $distribution = $distributionApproveForm->save();
        flash(__('distribution.approved'), 'success');

        return redirect()->route('distributions.show', $distribution);
    }
}
