<?php

namespace App\Jobs;

use App\Mail\CustomReminderEmail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CustomReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $customReminder;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customReminder)
    {
        $this->customReminder = $customReminder;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emails = $this->customReminder->emails;
        // start_date format = YYYY-MM-DD
        $start_date = $this->customReminder->start_date;

        // Add delay if start_date is not today
        if($start_date != date('Y-m-d')){
            $start_date = Carbon::parse($start_date);
            $delay = $start_date->diffInDays(Carbon::now());
        }else{
            $delay = 0;
        }

        $delay = $this->customReminder->repetition;
        RepetitionCustomReminderJob::dispatch($this->customReminder)->delay($delay);

        for($i = $delay; $i < 0; $i--){
            if($delay != 0){
                RepetitionCustomReminderJob::dispatch($this->customReminder)->delay($delay);
            }
        }
    }
}
