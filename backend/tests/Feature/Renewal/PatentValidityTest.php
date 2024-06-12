<?php

namespace Tests\Feature\Renewal;

use App\Helpers\PatentRenewal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TestCase as ModelsTestCase;

class PatentValidityTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_patent_validity_test()
    {
        $patentRenewal = new PatentRenewal();
        $testResponse = ModelsTestCase::where('test_case_name', 'patent-validity')->first();

        $patentValidity = $patentRenewal->patent_validity($testResponse->response, 'patent');

        $this->assertTrue($patentValidity, 'The test has failed because the patent validity is not correct.');
    }
}
