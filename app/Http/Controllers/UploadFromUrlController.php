<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\NoReturn;

class UploadFromUrlController extends Controller
{
    #[NoReturn]
    public function __invoke()
    {
        //        $url = $request->uri();
        //        $image_contents = file_get_contents($url);
        //        if ($image_contents === false) {
        //            return response()->json(['message' => 'Failed to fetch image from URL'], 400);
        //        }
        //        $image_name = Str::random(10) . '.jpg';
        //        $image_path = public_path('uploads/' . $image_name);
        //        $save = file_put_contents($image_path, $image_contents);
        //        if ($save === false) {
        //            return response()->json(['message' => 'Failed to save image'], 500);
        //        }
        //        return response()->json([
        //            'message' => 'Image uploaded successfully',
        //            'image_url' => url('uploads/' . $image_name)
        //        ]);
        //        auth()->loginUsingId(1);
        dd(auth()->user()->name);
    }
}
