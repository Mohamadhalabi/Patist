<?php

namespace Tests\Feature\Renewal;

use App\Models\Reminder;
use App\Models\Renewal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Workflow\WorkflowStub;

class WorkflowInstruction1Test extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_new_instruction_workflow_test()
    {
        // In the workflow, each step is marked as successful.

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
        $workflow->setStatus("RNWo.1.2", true);
        $workflow->setStatus("RNWo.2.1", true);
        $workflow->setStatus("RNWo.2.2", true);
        $workflow->setStatus("RNWo.2.3", true);
        $workflow->setStatus("RNWo.3", true);
        $workflow->setStatus("RNWo.4", true);

        // delete renewal and reminder
        Renewal::find($setInstruction['renewal_id'])->delete();
        Reminder::find($setInstruction['reminder_id'])->delete();

        // return true
        $this->assertTrue(true);
    }
}
