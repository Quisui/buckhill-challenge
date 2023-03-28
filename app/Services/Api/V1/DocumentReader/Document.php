<?php

namespace App\Services\Api\V1\DocumentReader;

interface Document
{
    public function readFile($file, ...$options);
}
