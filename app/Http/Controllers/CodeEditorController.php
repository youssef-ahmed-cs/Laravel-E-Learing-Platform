<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeEditorManagement\CodeEditorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CodeEditorController extends Controller
{
    private $pistonBaseUrl = 'https://emkc.org/api/v2/piston';

    /**
     * Get available runtimes
     */
    public function getRuntimes()
    {
        try {
            $response = Http::get($this->pistonBaseUrl . '/runtimes');

            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch runtimes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Execute code from form-data
     */
    public function executeCode(CodeEditorRequest $request)
    {
        $validated = $request->validated();

        $extension = $this->getFileExtension($validated['language']);

        $pistonPayload = [
            'language' => $validated['language'],
            'version' => $validated['version'],
            'files' => [
                [
                    'name' => 'main.' . $extension,
                    'content' => $validated['code']
                ]
            ]
        ];

        try {
            $response = Http::timeout(35)
                ->post($this->pistonBaseUrl . '/execute', $pistonPayload);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to execute code',
                'error' => $response->body()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error executing code',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get file extension based on language
     */
    private function getFileExtension($language)
    {
        $extensions = [
            'python' => 'py',
            'javascript' => 'js',
            'java' => 'java',
            'php' => 'php',
            'cpp' => 'cpp',
            'c' => 'c',
            'csharp' => 'cs',
            'go' => 'go',
            'rust' => 'rs',
            'ruby' => 'rb',
            'typescript' => 'ts',
            'kotlin' => 'kt',
            'swift' => 'swift',
            'bash' => 'sh',
            'r' => 'r',
            'perl' => 'pl',
            'scala' => 'scala',
            'haskell' => 'hs',
        ];

        return $extensions[strtolower($language)] ?? 'txt';
    }
}
