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
            return $response->json('answer');
        }

        throw new \Exception('Debugging API failed: ' . $response->body());
    }
    
}
