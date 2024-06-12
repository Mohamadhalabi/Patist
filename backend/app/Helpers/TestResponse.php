<?php

namespace App\Helpers;

class TestResponse
{
    private $data = [];

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getResponse()
    {
        return $this->data;
    }
}
