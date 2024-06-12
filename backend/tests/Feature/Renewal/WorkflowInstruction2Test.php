<?php

namespace Tests\Feature\Renewal;

use App\Models\Reminder;
use App\Models\Renewal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Workflow\WorkflowStub;

class WorkflowInstruction2Test extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_workflow_instruction_test2()
    {
        // Delete renewal/reminder/workflow if continuation status in RNWo.1.2 is false

        // call patentRenewal helper
        $patentRenewal = new \App\Helpers\PatentRenewal();

        // set reference
        $reference = "TST_2020/08705";

        // set workflow
        $setInstruction = $patentRenewal->set_workflow($reference);

        // workflow id
        $workflow_id = $setInstruction['workflow_id'];

        // load workflow
        $workflow = WorkflowStub::load($workflow_id);

        // set workflow status
        $workflow->setStatus("RNWo.1.1", true);
        $workflow->setStatus("RNWo.1.2", false);

        // delete renewal and reminder
        Renewal::find($setInstruction['renewal_id'])->delete();
        Reminder::find($setInstruction['reminder_id'])->delete();

        // return true
        $this->assertTrue(true);
    }
}
