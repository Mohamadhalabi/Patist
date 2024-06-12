<?php

namespace Tests\Feature\Renewal;

use App\Helpers\PatentRenewal;
use App\Models\TestCase as ModelsTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExistigPatentNumberTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_existing_patent_number_test()
    {
        $patentRenewal = new PatentRenewal();
        $testResponse = ModelsTestCase::get();
        $patentNumber = "2023/99999";

        $count = $patentRenewal->is_existing($testResponse, $patentNumber);

        $this->assertTrue($count > 1, 'The test has failed because the patent number exists multiple times.');
    }
}
