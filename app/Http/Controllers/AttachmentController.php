<?php

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class AttachmentController extends Controller
{
    private $tempImagePath = 'app/avatars/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5000|mimes:' . $this->getAllowedFileTypes(),
            'attachable_id' => 'required|integer',
            'attachable_type' => 'required',
        ]);


        // create a random string filename
        $filename = $this->generateFilename($request->file);
        // resize the image
        $image = $this->resizeImage($request->file('file'), $filename);

        if($this->uploadedImage($filename, $image)) {
            return Attachment::create([
                'filename' => $request->file->getClientOriginalName(),
                'uid' => "avatars/$filename",
                'size' => $request->file->getClientSize(),
                'mime' => $request->file->getMimeType(),
                'attachable_id' => $request->get('attachable_id'),
                'attachable_type' => $request->get('attachable_type'),
            ]);
        }

        return response(['msg' => 'Unable to upload your file.'], 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function edit(Attachment $attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachment $attachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attachment  $attachment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        return (string) $attachment->delete();
    }

    /**
     * Remove . prefix so laravel validator can use allowed files
     * 
     * @return string
    */
    private function getAllowedFileTypes()
    {
        return str_replace('.', '', config('attachment.allowed', ''));
    }

    private function resizeImage($image, $filename)
    {
        $imageManager = new ImageManager();

        $resizedImage = $imageManager->make($image)->resize(600,600, function($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path($this->tempImagePath . $filename)); 

        return $resizedImage;
    }

    private function generateFilename($file)
    {
        return str_random(20) . '.' . $file->getClientOriginalExtension();
    }

    private function uploadedImage($filename, $image)
    {
        $uploaded = Storage::disk('s3')->put("avatars/$filename", $image->__toString());
        // delete tmp image
        File::delete(storage_path($this->tempImagePath . $filename));

        return $uploaded;
    }
}
