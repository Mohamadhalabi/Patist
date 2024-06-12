<?php

namespace Tests\Feature\Renewal;

use App\Helpers\PatentRenewal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TestCase as ModelsTestCase;

class SetStatusTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_set_status_test()
    {
        $patentRenewal = new PatentRenewal();
        $testResponse = ModelsTestCase::where('test_case_name', 'set-status')->first();

        $setStatus = $patentRenewal->set_status($testResponse, true);

        $this->assertTrue($setStatus, 'The test has failed because the status is not set correctly.');
    }
}
