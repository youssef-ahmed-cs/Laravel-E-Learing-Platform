<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __invoke()
    {
        $files = request()->file('avatar');
        $name = 'youssef.' . $files->getClientOriginalExtension();
        # create directory with name Laravel-Files in storage/app
//        return Storage::makeDirectory('Laravel-Files');

        # store a file in storage/app/Laravel-Files with original name
//        Storage::putFileAs('avatars', $files,$name,'public');
//        return response()->json(['message' => 'File uploaded successfully']);

        # copy file from storage/app/private/avatars to storage/app/public/avatars
//        Storage::copy('private/avatars/youssef.png', 'public/avatars/youssef.png');
//        return response()->json(['message' => 'File copied successfully']);

        # move file from storage/app/private/avatars to storage/app/public/avatars
//        Storage::move('private/avatars/youssef.png', 'public/avatars/youssef.png');
//        return response()->json(['message' => 'File moved successfully']);

        # delete file from storage/app/public/avatars
//        Storage::delete('storage/app/public/avatars/320_Youssef Ahmed.jpg');
//        return response()->json(['message' => 'File deleted successfully']);

        # get file url from storage/app/public/avatars
//        $url = Storage::url('avatars/' . $name);
//        return response()->json(['url' => $url]);

        # get file path from storage/app/public/avatars
//        $path = Storage::path('avatars/' . $name);
//        return response()->json(['path' => $path]);

        # check if file exists in storage/app/public/avatars
//        $exists = Storage::exists('avatars/' . $name);
//        return response()->json(['exists' => $exists]);

        # get file size from storage/app/public/avatars in KB
//        $size = Storage::size('avatars/' . $name);
//        return response()->json(['size' => $size/1024 . ' KB']);

        # get file last modified time from storage/app/public/avatars
//        $time = Storage::lastModified('avatars/' . $name);
//        return response()->json(['last_modified' => date('Y-m-d H:i:s', $time)]);

        // get all files in storage/app/public/avatars
//        $files = Storage::files('public');
//        return response()->json(['files' => $files]);

        // get all files in storage/app/public/avatars and its subdirectories
//        $allFiles = Storage::allFiles('avatars');
//        return response()->json(['all_files' => $allFiles]);

        // get all directories in storage/app/public
//        $directories = Storage::directories('public');
//        dd($directories);
//        return response()->json(['directories' => $directories]);


    }
}
