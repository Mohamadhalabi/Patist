<?php

namespace App\Workflows\Renewal\Activities;

use App\Models\ReminderFragment;
use App\Models\Renewal;
use App\Notifications\Renewal\InstructionAcceptedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Workflow\Activity;

class ClientCommunicationToAcceptInstructionActivity extends Activity
{
    public function execute($renewal, $adminUser = null)
    {
        $renewal->reminder->status = 'RNWo.2.1';
        $renewal->reminder->save();

        // $user değişkeni oluştur. eğer adminUser boşsa $renewal->user ile doldur.
        $user = $renewal->user;
        
        $user->notify(new InstructionAcceptedNotification($renewal));
        \App\Models\Email::create([
            'renewal_id' => $renewal->reminder->id,
            'type' => 'client',
            'title' => 'Instruction Accepted Email',
            'description' => 'An email has been sent to the user confirming that the instruction has been received.',
            'content' => ("Instruction Accepted - {$renewal->reference_no}"),
            'reminder_id' => $renewal->reminder->id,
            'renewal_id' => $renewal->id,
        ]);
    }
}
