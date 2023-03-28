<?php

return [
    'test/type' => App\Services\Api\V1\DocumentReader\TestTypeFile::class,
    'text/plain' => App\Services\Api\V1\DocumentReader\TextFileReader::class,
    'octet-stream' => App\Services\Api\V1\DocumentReader\TextFileReader::class,
    'application/pdf' => App\Services\Api\V1\DocumentReader\PDFFileReader::class,
];
