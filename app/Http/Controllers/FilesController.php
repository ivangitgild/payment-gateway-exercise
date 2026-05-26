<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FilesController extends Controller
{

    /**
     * @param Request $request
     * @return json
     */
    public function upload(Request $request) {

        $request->validate([
            'file' => 'required|file|max:20480',
            'bucket' => 'required|string',
        ]);

        $uploadedFile = $request->file('file');

        $bucket = trim($request->bucket, '/');

        $filename = Str::uuid() . '.' .
            $uploadedFile->getClientOriginalExtension();

        $path = $uploadedFile->storeAs(
            $bucket,
            $filename,
            'public'
        );

        $file = File::create([
            'disk' => 'public',
            'bucket' => $bucket,
            'path' => $path,
            'filename' => $filename,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
            'size' => $uploadedFile->getSize(),
        ]);

        return response()->json([
            'id' => $file->id,
            'filename' => $file->original_name,
            'path' => $file->path,
            'bucket' => $file->bucket,
        ]);
    }

    public function download($id) {
        $file = File::findOrFail($id);

        if (!Storage::disk($file->disk)->exists($file->path)) {
            abort(404, 'File not found');
        }

        // return Storage::disk($file->disk)->download(
        //     $file->path,
        //     $file->original_name
        // );

        return response()->json([
            'id' => $file->id,
            'filename' => $file->original_name,
            'path' => Storage::url($file->path),
            'bucket' => $file->bucket,
        ]);
    }
}
