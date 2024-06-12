<?php

namespace Tests\Feature\Renewal;

use App\Helpers\PatentRenewal;
use App\Models\TestCase as ModelsTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WrongPatentNumberTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_the_wrong_patent_number_test()
    {
        $patentRenewal = new PatentRenewal();
        $testResponse = ModelsTestCase::where('test_case_name', 'wrong-patent-number')->first();

        $this->assertTrue($patentRenewal->is_wrong($testResponse->test_case_response), 'The test has failed because the patent number is correct.');
    }
}
