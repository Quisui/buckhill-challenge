<?php

namespace App\Services\Api\V1\DocumentReader;

class TestTypeFile implements Document
{
    public function readFile($file, ...$options)
    {
        return true;
    }
}
