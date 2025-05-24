<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ProblemScraperService
{
    protected string $baseUrl;

    public function __construct()
    {
        // Base URL of your FastAPI server
        $this->baseUrl = config('services.scraper.url', 'http://localhost:5000');
    }

    /**
     * Call the FastAPI /scrape endpoint with a problem URL.
     */
    public function fetchProblemData(string $url): ?array
    {
        try {
            $response = Http::post($this->baseUrl . '/scrape', [
                'url' => $url
            ]);

            if ($response->successful()) {
                return $response->json();
            }

        } catch (\Exception $e) {
            // You could log the error if needed: 
            Log::error($e->getMessage());
        }

        return null;
    }
}
