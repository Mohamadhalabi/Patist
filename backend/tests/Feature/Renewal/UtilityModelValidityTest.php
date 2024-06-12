<?php

namespace Tests\Feature\Renewal;

use App\Helpers\PatentRenewal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TestCase as ModelsTestCase;

class UtilityModelValidityTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_utility_model_validity_test()
    {
        $patentRenewal = new PatentRenewal();
        $testResponse = ModelsTestCase::where('test_case_name', 'utility-model-validity')->first();

        $utilityModelValidity = $patentRenewal->patent_validity($testResponse->response, 'utility-model');

        $this->assertTrue($utilityModelValidity, 'The test has failed because the utility model validity is not correct.');
    }
}
