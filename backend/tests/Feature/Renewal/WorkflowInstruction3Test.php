<?php

namespace Tests\Feature\Renewal;

use App\Models\Reminder;
use App\Models\Renewal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Workflow\WorkflowStub;

class WorkflowInstruction3Test extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_workflow_instruction_test2()
    {
        // all renewal processes are successful and the next renewal request is active

        // call patentRenewal helper
        $patentRenewal = new \App\Helpers\PatentRenewal();

        // set reference
        $reference = "TST_2020/08705";

        // set workflow
        $setInstruction = $patentRenewal->set_workflow($reference);

        $renewal = Renewal::find($setInstruction['renewal_id']);
        $reminder = Reminder::find($setInstruction['reminder_id']);
        // workflow id
        $workflow_id = $setInstruction['workflow_id'];

        // load workflow
        $workflow = WorkflowStub::load($workflow_id);

        // set workflow status
        $workflow->setStatus("RNWo.1.1", true);
        $workflow->setStatus("RNWo.1.2", true);
        $workflow->setStatus("RNWo.2.1", true);
        $workflow->setStatus("RNWo.2.2", true);
        $workflow->setStatus("RNWo.2.3", true);
        $workflow->setStatus("RNWo.3", true);

        // set status to follow next year
        $reminder->workflow_status = 'beign-followed';
        $workflow->setStatus("RNWo.4", true);


        // delete renewal and reminder
        $renewal->delete();
        $reminder->delete();

        // return true
        $this->assertTrue(true);
    }
}
