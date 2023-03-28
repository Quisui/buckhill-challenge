<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Api\V1\SaveDocument;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Controllers\StoreFileRequest;
use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFileRequest $request, SaveDocument $saveDocument)
    {
        $saveDocument->execute($request->all());
        return redirect(route('documents.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\File  $file
     *
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\File  $file
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\File  $file
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
    }
}
