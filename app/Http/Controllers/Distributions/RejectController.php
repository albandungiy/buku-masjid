<?php

namespace App\Http\Controllers\Distributions;

use App\Http\Controllers\Controller;
use App\Http\Requests\Distributions\RejectRequest;
use App\Models\Distribution;

class RejectController extends Controller
{
    // See ApproveController@update for why the unused $distribution parameter matters:
    // without it, {distribution} never gets implicitly bound and every policy check in
    // RejectRequest::authorize() fails closed with a 403, regardless of role.
    public function update(RejectRequest $distributionRejectForm, Distribution $distribution)
    {
        $distribution = $distributionRejectForm->save();
        flash(__('distribution.rejected'), 'warning');

        return redirect()->route('distributions.show', $distribution);
    }
}
