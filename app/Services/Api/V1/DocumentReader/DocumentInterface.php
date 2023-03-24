<?php

namespace App\Services\Api\V1\DocumentReader;

use PhpOffice\PhpSpreadsheet\IOFactory;

interface DocumentInterface
{
    public function readFile($file, ...$options);
}
