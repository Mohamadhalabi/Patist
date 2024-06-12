<?php

namespace App\Workflows\Renewal;

use App\Models\Renewal;
use Workflow\Activity;

class ReminderAddedStatusActivity extends Activity
{
    public function execute($renewal, $status)
    {
        // Reminder added successfully
        $reminder = $renewal->reminder;
        if($status){
            $reminder->workflow_status = 'being-followed';
        }
        // Reminder added failed
        else{
            // Include reason for no adding from one of those options: (expiration, abondon, other)
            $reminder->workflow_status = $renewal->reason;
        }
    }
}
