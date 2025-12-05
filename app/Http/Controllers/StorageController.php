<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageController extends Controller
{
    public function getStorageFile(Request $request)
    {
        $filename = $request->query('filename');
        if (Str::contains($filename, '..')) {
            abort(400, 'Invalid filename');
        }

        if (!Storage::disk('public')->exists($filename)) {
            abort(404, 'File not found');
        }

        $file = Storage::disk('public')->get($filename);
        $mime = Storage::disk('public')->mimeType($filename);

        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept');
    }
}
