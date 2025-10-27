<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImageGenController extends Controller
{
    public function generate(Request $request)
    {
        $prompt = $request->input('prompt', 'A futuristic Laravel logo floating in space');

        $apiKey = env('GEMINI_API_KEY');

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-image:generateContent';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-goog-api-key' => $apiKey,
        ])->post($url, [
            'contents' => [[
                'parts' => [['text' => $prompt]]
            ]]
        ]);

        if ($response->failed()) {
            return response()->json(['success' => false, 'error' => $response->body()], 500);
        }

        $json = $response->json();
        $base64Data = $json['candidates'][0]['content']['parts'][0]['inline_data']['data'] ?? null;

        if (!$base64Data) {
            return response()->json(['success' => false, 'error' => 'No image returned'], 500);
        }

        $image = base64_decode($base64Data);
        $fileName = 'gemini_' . time() . '.png';
        file_put_contents(public_path('images/' . $fileName), $image);

        return response()->json([
            'success' => true,
            'message' => 'Image generated successfully',
            'url' => asset('images/' . $fileName)
        ]);
    }
}
