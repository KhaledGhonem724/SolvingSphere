<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OllamaHintService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.ollama_hint.url');
    }

    public function getDebugHint(string $code): string
    {
        $response = Http::post($this->baseUrl . '/debug', [
            'code' => $code,
        ]);

        if ($response->successful()) {
            $json = $response->json();
            return $json['answer'] ?? 'No answer received.';
        }

        Log::error('OllamaHintService error', [
            'status' => $response->status(),
            'body' => $response->body()
        ]);

        throw new \Exception('Failed to get debug hint from Ollama service.');
    }
}
