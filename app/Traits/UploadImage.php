<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadImage
{
    public function uploadImage(UploadedFile $file, string $directory = 'images'): string
    {
        return $file->store($directory, 'public');
    }

    public function deleteImage(string $path): bool
    {
        return Storage::disk('public')->delete($path);
    }
}
