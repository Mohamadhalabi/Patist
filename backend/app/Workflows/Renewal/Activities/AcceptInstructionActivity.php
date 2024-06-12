<?php

namespace App\Workflows\Renewal\Activities;

use App\Models\Renewal;
use Illuminate\Support\Facades\Log;
use Workflow\Activity;

class AcceptInstructionActivity extends Activity
{
    public function execute($renewal = null)
    {
        $renewal->is_approved = true;
        $renewal->save();

        $renewal->reminder->status = 'RNWo.1.2';
        $renewal->reminder->save();
    }
}
