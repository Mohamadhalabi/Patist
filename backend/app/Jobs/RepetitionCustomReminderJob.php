<?php

namespace App\Jobs;

use App\Mail\CustomReminderEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RepetitionCustomReminderJob implements ShouldQueue
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
        Mail::to($this->customReminder->emails)->send(new CustomReminderEmail($this->customReminder));
    }
}
