<?php

namespace App\Workflows\Renewal;

use App\Models\Renewal;
use Workflow\ActivityStub;
use Workflow\SignalMethod;
use Workflow\Workflow;
use Workflow\WorkflowStub;

class ExistingRenewalInstructionWorkflow extends Workflow
{
    // Properties to be used in the workflow
    private $status = [
        'RNWo.1.1' => false,
        'RNWo.1.2' => false,
        'RNWo.1.3' => false,
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
         * Document Ref.Code : RNWe.1.1
         * Ref.ShortName : Being monitored
         * Ref.Status :Waiting on renewalinternal deadline date
         * Actions : Monitor renewal deadline
         *           Trigger at internal deadline
         *              - Send an internal reminder
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.1.1']);
        yield ActivityStub::make(SendInternalReminderActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWe.1.2
         * Ref.ShortName : Upcoming renewal
         * Ref.Status :Waiting on approval for upcoming renewal client reminder/quote
         * Actions : Do initial checks
         *           Decide if renewal will be performed if pament is not made
         *           Send client reminder
         *              - Send quote
         *              - Ask for payments certainly if required
         *              - Ask user for instructions
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.1.2']);
        yield ActivityStub::make(ClientCommunicationToAcceptInstructionActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWe.1.3
         * Ref.ShortName : Instructions asked
         * Ref.Status : Waiting on client response to upcoming renewal client reminder
         * Actions : Wait response
         *           Follow response
         *              - Reach the client before deadline
         *           Solve any problems in instructions
         *              - Process response and add to the task system
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.1.3']);

        /**
         * Document Ref.Code : RNWe.2.1
         * Ref.ShortName : Instructions received and accepted(w.n.p)
         * Ref.Status : Waiting on for payments
         * Actions : Follow payments
         *            - Reminder for payments if prepayment required
         *            - Reminder for payments if overdue
         *           If payment not required
         *              - Internal notification to performer to start the renewal
         *              - Go to RNWe.2.3
         */

        yield ActivityStub::make(PaymentReminderActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWe.2.2
         * Ref.ShortName : Payment received
         * Ref.Status : (Auxiliary step)
         * Actions : If payment required
         *              - Internal notification to performer to start the renewal
         *              - Go to RNWe.2.3
         */

        yield ActivityStub::all([
            // Admin confirmed renewal possible
            WorkflowStub::await(fn () => $this->status['RNWo.2.1']),

            // User/admin reported payment made
            WorkflowStub::await(fn () => $this->status['RNWo.2.2']),
        ]);

        /**
         * Document Ref.Code : RNWe.2.3
         * Ref.ShortName : Beign renewed
         * Ref.Status : Waiting on the task to be performed
         * Actions : Follow the execution of the task before deadline
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.2.3']);
        yield ActivityStub::make(RenewalApprovedActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWe.3
         * Ref.ShortName : Renewed
         * Ref.Status : Instruction finished(w.n.p)
         * Actions : Issue invoice
         *           Send client notifications
         *              - Success notification
         *              - Send renewal docs
         *              - Send invoice
         */

        yield WorkflowStub::await(fn () => $this->status['RNWo.3']);
        yield ActivityStub::make(SendRenewalCompletedEmailActivity::class, $renewal);

        /**
         * Document Ref.Code : RNWe.4
         * Ref.ShortName : Instruction done
         * Ref.Status : Client notified(w.n.p)
         * Actions : Add reminders for the next renewal

         * Document Ref.Code : RNWe.5.a
         * Ref.ShortName : Reminder added for next year
         * Ref.Status :
         *
         * Document Ref.Code : RNWe.5.b
         * Ref.ShortName : Reminder not added
         * Ref.Status : Include reason for not adding from one of those options: (expiration, abondon, other)
         */

        if($this->status['RNWo.4'] == true)
        {
            yield ActivityStub::make(AddRemindersForNextRenewalActivity::class, $renewal);
        }
    }
}
