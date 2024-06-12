<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ApiAccessTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_api_access()
    {
        $number = "2020/08705";
        $content = file_get_contents('https://portal.turkpatent.gov.tr/anonim/arastirma/patent/sonuc/detay', false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'content' => http_build_query([
                    'partial' => true, 'applicationNumber' => str_replace('-', '/', $number)
                ])
            ]
        ]));

        $this->assertNotEmpty($content);
    }
}
