<?php

namespace App\Http\Controllers;

use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

class SendSmsController extends Controller
{
    public function __invoke()
    {
        define("BRAND_NAME", "Laravel");
        $basic = new Basic("8be99fed", "j4HWryWOxwvo1tb0");
        $client = new \Vonage\Client($basic);
        $response = $client->sms()->send(
            new SMS("+201277672245", BRAND_NAME, 'From Youssef Test API!')
        );

        $message = $response->current();
        return response()->json([
            'status' => $message->getStatus(),
            'message-id' => $message->getMessageId(),
            'to' => $message->getTo()
        ]);
    }
}
//
