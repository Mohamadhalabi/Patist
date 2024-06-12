<?php

namespace App\Workflows\Renewal;

use App\Models\Renewal;
use App\Workflows\Renewal\Activities\AcceptInstructionActivity;
use App\Workflows\Renewal\Activities\AddRemindersForNextRenewalActivity;
use App\Workflows\Renewal\Activities\ClientCommunicationToAcceptInstructionActivity;
use App\Workflows\Renewal\Activities\PaymentReminderActivity;
use App\Workflows\Renewal\Activities\RenewalApprovedActivity;
use App\Workflows\Renewal\Activities\SendRenewalCompletedEmailActivity;
use Workflow\ActivityStub;
use Workflow\SignalMethod;
use Workflow\Workflow;
use Workflow\WorkflowStub;

class TestWorkflow extends Workflow
{
    // Properties to be used in the workflow
    private $status = [
        'RNWo.1.1' => false,
        'RNWo.1.2' => false,
        'RNWo.2.1' => false,
        'RNWo.2.2' => false,
        'RNWo.2.3' => false,
        'RNWo.3' => false,
        'RNWo.4' => false,
        'RNWo.5.a' => true,
        'RNWo.5.b' => true,
    ];

    private $reminderTime = 10;

    #[SignalMethod]
    public function setStatus($code, $bool)
    {
        $this->status[$code] = $bool;
    }

    #[SignalMethod]
    public function setReminderTime($day)
    {
        $this->reminderTime = $day;
    }

    /**
     * Execute
     *
     * @param  mixed $renewal
     * @param  mixed $reminder
     * @return void
     */
    public function execute($renewal = [], $reminder = [])
    {
        /**
         * Document Ref.Code : RNWo.1.1
         * Ref.ShortName : Instruction received
         * Ref.Status : Instructions received from online channel
         * Actions : Acknowledge receipt
         *              - Send automated response
         *              - Let knowan expert will contaxt
         *              - Point to how payments can be made
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.1.1']);
        yield ActivityStub::make(AcceptInstructionActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWo.1.2
         * Ref.ShortName : Instructions acknowledged
         * Ref.Status : Waiting for approval
         * Actions : Do initial checks
         *           Client communication to accept instructions
         *              - Solve any problems in instructions: Process response and add to the task system
         *              - Let know of manual expert check
         *              - Let know of acceptance
         *              - Ask for payments
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.1.2']);
        yield ActivityStub::make(ClientCommunicationToAcceptInstructionActivity::class, $renewal);

        /**
         *
         * Document Ref.Code : RNWo.2.1
         * Ref.ShortName : Instructions received and accepted(w.n.p)
         * Ref.Status : Waiting on for payments
         * Actions : Follow payments
         *             - Reminder for payments if necessary
         **/

        yield ActivityStub::make(PaymentReminderActivity::class, $renewal, $this->reminderTime);

        /**
         *
         * Document Ref.Code : RNWo.2.2
         * Ref.ShortName : Payment received
         * Ref.Status :
         * Actions : Internal notification to performer to start the renewal
         *           Go to RNWe.2.3
         */

        yield ActivityStub::all([
            // Admin confirmed renewal possible
            yield WorkflowStub::await(fn () => $this->status['RNWo.1.2']),

            // User/admin reported payment made
            yield WorkflowStub::await(fn () => $this->status['RNWo.2.2']),
        ]);

        /**
         * Document Ref.Code : RNWo.2.3
         * Ref.ShortName : Being received
         * Ref.Status : Waiting on the task to be performed
         * Actions : Follow the execution of the task before deadline
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.2.3']);
        yield ActivityStub::make(RenewalApprovedActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWo.3
         * Ref.ShortName : Renewed
         * Ref.Status : Instruction finished(w.n.p)
         * Actions : Issue invoice
         *           Send client notifications
         *               - Success notification
         *               - Send renewal docs
         *               - Send invoice
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.3']);
        yield ActivityStub::make(SendRenewalCompletedEmailActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWo.4
         * Ref.ShortName : Instruction done
         * Ref.Status : Client notified(w.n.p)
         * Actions : Add reminders for the next renewal

         * Document Ref.Code : RNWo.5.a
         * Ref.ShortName : Workflow finished
         * Ref.Status : Reminder added for next year
         * Actions :
         *
         * Document Ref.Code : RNWo.5.b
         * Ref.ShortName : Workflow finished
         * Ref.Status : Reminder not added
         * Actions : Include reason for no adding from one of those options: (expiration, abondon, other)
         */

        // Will be renewed next year
        yield WorkflowStub::await(fn () => $this->status['RNWo.4']);
        yield ActivityStub::make(AddRemindersForNextRenewalActivity::class, $renewal);
    }
}
