<?php

namespace App\Services\Api\V1\DocumentReader;

use Smalot\PdfParser\Parser;

class PDFFileReader implements Document
{
    public function readFile($file, ...$options)
    {
        $parser = new Parser();
        $contents = $parser->parseFile($file);

        return $contents->getText();
    }
}
