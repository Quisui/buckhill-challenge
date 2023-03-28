<?php

namespace App\Actions\Api\V1;

use App\Models\File;

class SaveDocument
{
    public function execute(array $upload): bool
    {
        $uploadedFile = $upload['document'];
        $file = $uploadedFile->store('documents');
        if (! $upload['filename']) {
            $originalFilename = basename($uploadedFile->getClientOriginalName(), '.' . $uploadedFile->getClientOriginalExtension());
        }

        $class = config('filereader.' . $upload['document']->getMimeType());

        $reader = new $class();
        $document = new File();
        $document->filename = $originalFilename ?? $upload['filename'];
        $document->location = $file;
        $document->body = $reader->getContents($upload['document']);
        $document->user_id = auth()->user()->id;

        $document->save();

        return true;
    }
}
