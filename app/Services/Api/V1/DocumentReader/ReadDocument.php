<?php

namespace App\Services\Api\V1\DocumentReader;

use Error;
use Illuminate\Http\Response;

class ReadDocument
{
    public static function resolveFactory($importerType)
    {
        $documentType = $importerType;
        if (!app()->environment('testing'))
            $documentType =  $importerType->getMimeType();
        $class = config('filereader.' . $documentType);
        abort_if(empty($class), Response::HTTP_BAD_REQUEST, 'Factory name could not be resolved.');

        return new $class;
    }

    public function readDocument(string $method, $options)
    {
        abort_if(!array_key_exists('facadeName', $options), Response::HTTP_BAD_REQUEST);

        return (self::resolveFactory($options['facadeName']))->$method(...$options);
    }
}
