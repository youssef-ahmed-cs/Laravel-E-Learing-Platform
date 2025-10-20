<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;

class LearnHttpController extends Controller
{
    /**
     * @throws ConnectionException
     */
    public function __invoke()
    {
        return response('hi', 200);
    }
}
/*
 * body -> get the body of the response as a string
 * JSON -> get the body of the response as an array or object
 * ok -> determine if the response has a 200-level status code <status code 200-299>
 * successful -> determine if the response has a 200-level status code <status code 200-299>
 * failed -> determine if the response has a 400 or 500-level status code <status code 400-599>
 * clientError -> determine if the response has a 400-level status code <status code 400-499>
 * serverError -> determine if the response has a 500-level status code <status code 500-599>
 * status -> get the status code of the response like 200, 404, 500 and so on
 * created -> determine if the response has a 201 status code
 * accepted -> determine if the response has a 202 status code
 * noContent -> determine if the response has a 204 status code
 * header -> get a specific header from the response
 * headers -> get all of the headers from the response
 * throw -> throw an exception if the response status code is >= 400
 * timeout -> determine if the response has a 408 status code
 * redirect -> determine if the response has a 300-level status code <status code 300-399>
 * */
