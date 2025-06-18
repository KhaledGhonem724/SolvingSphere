<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
    /*
    curl -X POST http://localhost:5000/submit -d {"url":"https://www.hackerearth.com/practice/algorithms/searching/linear-search/practice-problems/algorithm/count-mex-8dd2c00c/", "code":"secret","language":"cpp"}
    response:
        online_judge_response = str(oj_response),
        original_submission_link = str(submission_link)
    */
     public function fetchOnlineJudgeResponse(string $url,string $solution_code,string $programming_language): ?array
    {
        try {
            $response = Http::post($this->baseUrl . '/submit', [
                'url' => $url,
                'code' => $solution_code,
                'language' => $programming_language,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Adding result attribute
                // str_contains($data['online_judge_response'], 'Compilation Error')
                if (isset($data['online_judge_response']) && 
                    str_contains($data['online_judge_response'] , "Accepted")) 
                {
                    $data['result'] = 'succeeded';
                } else {
                    $data['result'] = 'failed'; 
                }

                //return response()->json($data);
                return $data;

            }


        } catch (\Exception $e) {
            // You could log the error if needed: 
            Log::error($e->getMessage());
        }

        // return null; // DID NOT GET IT // BUT IT IS BREAKING THE SYSTEM
        // return ["null"]; // DID NOT GET IT // BUT IT IS WORKING
        return $response;
    }
}






