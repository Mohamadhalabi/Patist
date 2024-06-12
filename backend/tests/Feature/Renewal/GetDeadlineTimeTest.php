<?php

namespace Tests\Feature\Renewal;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetDeadlineTimeTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_deadline_time_test()
    {
        $patentRenewal = new \App\Helpers\PatentRenewal();

        $renewal = \App\Models\Renewal::first();
        $patentRenewal->get_deadline_time($renewal);

        $this->assertTrue(true);
    }
}
