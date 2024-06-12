<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class UpdateEmailForExhangeInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update email for exhange information';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arrayEmails = [env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_ADDRESS2')];
        $emailSubject = 'Currency Information Update';
        $emailBody = 'Do not forget to update the official fees information in the system for the next year.';

        Mail::send('emails.normal',
            ['msg' => $emailBody],
            function($message) use ($arrayEmails, $emailSubject) {
                $message->to($arrayEmails)
                ->subject($emailSubject);
            }
        );
    }
}
