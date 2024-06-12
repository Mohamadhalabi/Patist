<?php

namespace App\Workflows\Renewal\Activities;

use App\Jobs\DeadlineReminder;
use App\Models\Reminder;
use App\Models\ReminderFragment;
use App\Models\Renewal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Workflow\Activity;

class RenewalApprovedActivity extends Activity
{
    public function execute($renewal = null)
    {
        Log::info('RenewalApprovedActivity');
        $renewal->is_completed = true;
        $renewal->save();

        $renewal->reminder->status = 'RNWo.3';
        $renewal->reminder->save();

        $renewal = Renewal::first();

        $renewal_date = $renewal->renewal_date;
        $renewal_date = Carbon::createFromFormat('Y-m-d', $renewal_date);
        $current_date = Carbon::now();
        $days_difference = $current_date->diffInDays($renewal_date);

        // X days after the job is received
        // deadlinereminder a delay ekle. Gün sayısı : $renewal->updated_at + 5 gün
        DeadlineReminder::dispatch($renewal)->delay(now()->addDays(5));
        $reminder = $renewal->reminder;
        $fragment = new ReminderFragment();
        Log::info($renewal);
        $reminder_data = Reminder::where('renewal_id', $renewal->id)->first();
        Log::info($reminder_data);
        $fragment->reminder_id = $reminder_data->id;
        $fragment->title = $reminder->ref_code . ' Deadline Reminder';
        //YYYY-MM-DD şeklinde tarihi al

        $fragment->start_date = now()->addDays(5)->format('Y-m-d'). ' 09:00:00';
        $fragment->end_date = now()->addDays(5)->format('Y-m-d'). ' 09:15:00';
        $fragment->save();


        // there are X days left to the deadline
        DeadlineReminder::dispatch($renewal)->delay(now()->addDays($days_difference - 3));

        $reminder = $renewal->reminder;
        $fragment = new ReminderFragment();
        $fragment->reminder_id = $reminder_data->id;
        $fragment->title = $reminder->ref_code . ' Deadline Reminder';
        $fragment->start_date = now()->addDays($days_difference - 3)->format('Y-m-d'). ' 09:00:00';
        $fragment->end_date = now()->addDays($days_difference - 3)->format('Y-m-d'). ' 09:15:00';
        $fragment->save();
    }
}
