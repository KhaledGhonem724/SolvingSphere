<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ProblemSubmitterService
{
    protected string $baseUrl;

    public function __construct()
    {
        // Base URL of your FastAPI server
        $this->baseUrl = config('services.submitter.url', 'http://localhost:5000');
    }

    /**
     * Call the FastAPI /scrape endpoint with a problem URL.
     */
    public function fetchOnlineJudgeResponse(string $url,string $solution_code,string $programming_language): ?array
    {
        try {
            $response = Http::post($this->baseUrl . '/submit', [
                'url' => $url,
                'code' => $solution_code,
                'language' => $programming_language,
            ]);
            /*
            response:
                online_judge_response = str(oj_response),
                original_submission_link = str(submission_link)
            */
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
