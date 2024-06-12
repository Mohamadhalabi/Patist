<?php

namespace App\Workflows\Renewal;

use App\Jobs\ReminderEmailJob;
use Carbon\Carbon;
use Workflow\Activity;

class SendInternalReminderActivity extends Activity
{
    public function execute($renewal)
    {
        ReminderEmailJob::dispatch($renewal->user->email, $renewal);

        $renewal->user->notify(new \App\Notifications\Renewal\SendInternalReminderNotification($renewal));
        return "Payment reminder email sent to {$renewal->user->email}";
    }
}
