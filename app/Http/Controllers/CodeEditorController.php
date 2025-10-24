<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeEditorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CodeEditorController extends Controller
{
    public function __invoke(CodeEditorRequest $request)
    {
        $validated = $request->validated();

        try {
            $payload = [
                'language' => $validated['lang'],
                'version' => $validated['version'] ?? '3.10.0',
                'files' => [
                    [
                        'name' => 'main.' . $validated['lang'],
                        'content' => $validated['code']
                    ]
                ],
                'compile_timeout' => 10000,
                'run_timeout' => 3000
            ];


            $response = Http::timeout(10)
                ->acceptJson()
                ->post('https://emkc.org/api/v2/piston/execute', $payload);


            return $response->failed() ? response()->json(['error' => 'Code execution service unavailable'], 503) : $response->json();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Connection timeout'], 504);
        }
    }
}
