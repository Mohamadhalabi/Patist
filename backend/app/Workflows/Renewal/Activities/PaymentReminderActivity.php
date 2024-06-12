<?php

namespace App\Workflows\Renewal\Activities;

use App\Jobs\PaymentReminderJob;
use App\Models\ReminderFragment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Workflow\Activity;

class PaymentReminderActivity extends Activity
{
    public function execute($renewal, $reminderTime, $adminUser = null)
    {
        $renewal->reminder->status = 'RNWo.2.1';
        $email = $adminUser->email ?? $renewal->user->email;

        $reminder = $renewal->reminder;
        $fragment = new ReminderFragment();
        $fragment->reminder_id = $reminder->id;
        $fragment->title = $reminder->ref_code . ' Payment Reminder';
        $expiredDate = Carbon::parse($renewal->renewal_date);
        $fragment->start_date = $expiredDate->subDays(10)->format('Y-m-d'). ' 09:00:00';
        $fragment->end_date = $expiredDate->subDays(10)->format('Y-m-d'). ' 09:15:00';
        $fragment->save();

        PaymentReminderJob::dispatch($email, $renewal)->delay(now()->addDays($reminderTime));
    }
}
