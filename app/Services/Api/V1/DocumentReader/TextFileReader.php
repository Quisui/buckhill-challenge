<?php

namespace App\Services\Api\V1\DocumentReader;

class TextFileReader implements Document
{
    public function readFile($file, ...$options)
    {
        return file_get_contents($file);
    }
}
