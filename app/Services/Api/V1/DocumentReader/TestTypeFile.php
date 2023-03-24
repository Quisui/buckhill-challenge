<?php

namespace App\Services\Api\V1\DocumentReader;

use App\Services\Api\V1\DocumentReader\DocumentInterface;

class TestTypeFile implements DocumentInterface
{
    public function readFile($file, ...$options)
    {
        return true;
    }
}
