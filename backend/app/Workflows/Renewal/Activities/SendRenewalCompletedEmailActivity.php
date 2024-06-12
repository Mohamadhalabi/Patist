<?php

namespace App\Workflows\Renewal\Activities;

use App\Mail\RenewalCompleted;
use App\Models\Renewal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Workflow\Activity;

class SendRenewalCompletedEmailActivity extends Activity
{
    public function execute($renewal = null, $adminUser = null)
    {
        $renewal->reminder->status = 'RNWo.4';
        $renewal->reminder->save();
    }
}
