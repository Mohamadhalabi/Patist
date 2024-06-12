<?php

namespace Tests\Feature\Renewal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetDeadlineReminderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_set_deadline_reminder_test()
    {
        $patentRenewal = new \App\Helpers\PatentRenewal();

        $renewal = \App\Models\Renewal::first();
        $delay = 5;
        $patentRenewal->set_deadline_reminder_job($renewal, $delay);

        $this->assertTrue(true);
    }
}
