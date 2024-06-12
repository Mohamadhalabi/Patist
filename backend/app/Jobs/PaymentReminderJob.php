<?php

namespace App\Jobs;

use App\Mail\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PaymentReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $renewal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $renewal)
    {
        $this->email = $email;
        $this->renewal = $renewal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        if($this->renewal->reminder->send_email == true && $this->renewal->is_payment_made){
            Mail::to($this->email)->send(new PaymentReminder($this->renewal));

            \App\Models\Email::create([
                'renewal_id' => $this->renewal->reminder->id,
                'type' => 'client',
                'title' => 'Payment Reminder Email',
                'description' => 'An e-mail has been sent to the client stating that the payment is approaching.',
                'content' => $this->renewal->reference_no.' - Payment Reminder Email'
            ]);
        }
        else{
            $this->renewal->reminder->fragments()->delete();
        }
    }
}
