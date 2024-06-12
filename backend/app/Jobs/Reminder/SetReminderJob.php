<?php

namespace App\Jobs\Reminder;

use App\Mail\Reminder\ReminderMail;
use App\Models\ReminderList;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SetReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $reminder;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->reminder['status'] == 'inactive')
        {
            return;
        }

        $reminderList = new ReminderList();
        $reminderList->ref_code = $this->reminder['inputs']['ref_code'];
        $reminderList->short_name = $this->reminder['inputs']['short_name'];
        $reminderList->emails = json_encode($this->reminder['inputs']['emails']);
        if(isset($this->reminder['reminder_case_id']))
        {
            $reminderList->reminder_case_id = $this->reminder['reminder_case_id'];
        }
        if(isset($this->reminder['start_date']))
        {
            $reminderList->start_date = $this->reminder['start_date'];
        }
        if(isset($this->reminder['end_date']))
        {
            $reminderList->end_date = $this->reminder['end_date'];
        }
        if(isset($this->reminder['deadline']))
        {
            $reminderList->deadline = $this->reminder['deadline'];
        }
        $reminderList->frequency = $this->reminder['frequency'];
        $reminderList->status = 'active';
        $reminderList->save();

        // Send mail
        // Mail::to($this->reminder['inputs']['emails'])->send(new ReminderMail($this->reminder));

        /**
         * Reminder'ın tekrar etmesi için gereken şartlar:
         * 1. $reminderList->status == 'active'
         * 2. $reminderList->deadline > Carbon::now() ve ya $reminderList->end_date == 'infinite'
         */
        if($reminderList->status == 'active' && ($reminderList->deadline > Carbon::now() || $reminderList->end_date == 'infinite'))
        {
            // SetReminderJob::dispatch($this->reminder)->delay(now()->addMinutes(15));
        }
    }
}
