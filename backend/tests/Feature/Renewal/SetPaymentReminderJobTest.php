<?php

namespace Tests\Feature\Renewal;

use App\Models\Renewal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SetPaymentReminderJobTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_set_payment_reminder_job()
    {
        $patentRenewal = new \App\Helpers\PatentRenewal();
        $renewal = Renewal::first();
        $email = "test@test.com";
        $reminderTime = 5;
        $patentRenewal->set_payment_reminder_job($renewal, $email, $reminderTime);

        $this->assertTrue(true);
    }
}
