<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use JetBrains\PhpStorm\NoReturn;


class LearnHttpController extends Controller
{
    public function __invoke()
    {
//        return redirect()->route('tryRoute')->with('status', 'مرحبا بك في لارافيل');
//        return back()->with('status', 'مرحبا بك في لارافيل');
//        return to_route('tryRoute')->with('status', 'مرحبا بك في لارافيل');
//        return response()->view('welcome')->with('status', 'مرحبا بك في لارافيل');
//        return response()->file(storage_path('app/private/avatars/youssef.png')); # serve a file as a response
//          return response()->download(storage_path('app/private/avatars/youssef.png'), 'my_avatar.png', ['Content-Type' => 'image/png'])
//              ->deleteFileAfterSend(true); # serve a file as a response and delete it after sending
//        return response()->streamDownload(function () {
//            echo "Hello, this is a streamed download response.";
//        }, 'streamed_file.txt', ['Content-Type' => 'text/plain']);
        # with() => flash data to a session for one request only

    }
}
/*
 *       body returns the body of the response as a string
         json returns the body of the response as a json
         successful returns true if the response status code is 200-299
         status returns the status code of the response
         ok returns true if the response status code is 200
         collect returns the body of the response as a collection
         headers returns the headers of the response
         redirect returns the URL the response was redirected to
         clientError returns true if the response status code is 400-499
         serverError returns true if the response status code is 500-599
         throw throws an exception if the response status code is 400 or greater
         contentType sets the Content-Type header for the request
         withHeaders sets the headers for the request
         withToken sets the Authorization header for the request
         asForm sets the request's Content-Type to application/x-www-form-urlencoded
         asJson sets the request's Content-Type to application/json
         timeout sets the maximum number of seconds the request should take
         retry retries the request a given number of times if it fails

 * */
