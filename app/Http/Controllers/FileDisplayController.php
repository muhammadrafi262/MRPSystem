<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;

class FileDisplayController extends Controller
{
    public function show($folder, $flagOrFilename, $filename = null)
    {
        \Log::info('=== FILE DISPLAY DEBUG ===');
        \Log::info('Folder: ' . $folder);
        \Log::info('FlagOrFilename: ' . $flagOrFilename);
        \Log::info('Filename: ' . ($filename ?? 'NULL'));
        \Log::info('Allowed: ' . json_encode(['avatars', 'documents', 'thumbnails']));

        $allowedFolders = ['avatars', 'documents', 'thumbnails'];
        if (!in_array($folder, $allowedFolders)) {
            \Log::info('Folder TIDAK diizinkan, aborting!');
            abort(403, 'Akses folder tidak diizinkan');
        }
        // Jika $filename null berarti route tanpa flag dipakai
        if (is_null($filename)) {
            // berarti $flagOrFilename sebenarnya adalah filename
            $filename = basename($flagOrFilename);
            $folder2 = null;
        } else {
            // $flagOrFilename adalah folder2
            $folder2 = $flagOrFilename;

            // Anggap _ atau none berarti folder2 tidak ada
            if ($folder2 === '_' || $folder2 === 'none') {
                $folder2 = null;
            } else {
                $folder2 = basename($folder2);
            }
            $filename = basename($filename);
        }

        $folder = basename($folder);

        if ($folder2) {
            $path = public_path("$folder/$folder2/$filename");
        } else {
            $path = public_path("$folder/$filename");
        }

        if (!File::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file, 200)->header('Content-Type', $type);
    }

}
