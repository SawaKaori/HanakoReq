<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\UploadedFile;

class UploadController extends MyControllerBase
{
    public function upload(Request $request)
    {
        $request_id = $request->get('request_id', '');
        if ($request_id == '') {
            return $this->error401();
        }

        foreach ($request->file('files') as $index => $f) {
            $path = Storage::disk('s3')->putFile('/SiteReq', $f);
            $paths = explode('/', $path);
            $key = $paths[count($paths) - 1];

            $rec = new UploadedFile();
            $rec->request_id = $request_id;
            $rec->number = $index;
            $rec->original_name = $f->getClientOriginalName();
            $rec->s3_key = $key;
            $rec->save();
        }
        return response()->json(['result' => true]);
    }

    public function get(Request $request, $s3_key)
    {
        $key = 'SiteReq/' . $s3_key;
        $disk = Storage::disk('s3');
        try {
            if ($disk->exists($key)) {
                $rec = UploadedFile::where('s3_key', '=', $s3_key)->first();
                if (!$rec) {
                    $rec = new UploadedFile();
                    $rec->original_name = 'undefined.zip';
                }
                $contents = $disk->get($key);
                $contentType = $disk->mimeType($key);
                $filename = $rec->original_name;
                $headers = [
                    'Content-Type' => $contentType,
                    'Content-Disposition: attachment; filename="' . $filename . '"'
                ];
                return (new Response($contents, 200))
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
            }
        }
        catch(Exception $e) {
        }
        return $this->error401();
    }

    public function remove(Request $request, $id)
    {
        $file = UploadedFile::find($id);
        if (!$file) {
            return response()->json(['result' => true]);
        }
        $key = 'SiteReq/' . $file->s3_key;
        Storage::disk('s3')->delete($key);
        $file->delete();

        return response()->json(['result' => true]);
    }
}
