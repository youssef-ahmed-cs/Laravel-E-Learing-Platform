<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class LearnHttpController extends Controller
{
    /**
     * @throws ConnectionException
     * @throws RequestException
     */
    public function __invoke()
    {
        return Http::timeout(5)
            ->retry(3, 100)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->withToken('your-token-here')
            ->asJson()
            ->get('https://jsonplaceholder.typicode.com/posts/1')
            ->throw()
            ->json();
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
