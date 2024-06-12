<?php

namespace App\Jobs;

use App\Mail\SendDeadlineReminderEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DeadlineReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $renewal;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($renewal)
    {
        $this->renewal = $renewal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $admin_users_emails = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->pluck('email')->toArray();

        $data = [
            'subject' => 'Deadline Reminder',
            'body' => 'This is a reminder that the deadline is approaching.',
            'to' => $admin_users_emails,
        ];

        if(!$this->renewal->is_completed){
            Mail::to($this->renewal->user->email)->send(new SendDeadlineReminderEmail($this->renewal)); 
        }
    }
}
