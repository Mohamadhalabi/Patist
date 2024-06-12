<?php

namespace Tests\Feature\Renewal;

use App\Helpers\PatentRenewal;
use App\Models\Renewal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommunicationHistoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_communication_history_test()
    {
        $patentRenewal = new PatentRenewal();

        $user = User::first();
        $renewal = Renewal::first();
        $patentRenewal->create_communication_history_email($user, $renewal, 'client', 'Instruction Accepted', 'Description', 'Content');

        $this->assertTrue(true);
    }
}
