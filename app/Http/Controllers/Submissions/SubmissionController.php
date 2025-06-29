<?php

namespace App\Http\Controllers\Submissions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Services\ProblemSubmitterService;

use App\Models\Submission;
use App\Models\Problem;

use App\Services\OllamaHintService;

//use App\Http\Controllers\Problems\ProblemController;

class SubmissionController extends Controller
{
    // List all submissions
    public function index(Request $request)
    {
        $submissions = Submission::query();

        $user = Auth::user();
        $mineOnly = $request->boolean('my_submissions_only');

        if ($mineOnly && isset($user)) {
            $user_handle = $user->user_handle;
            $submissions->whereHas('owner', function ($query) use ($user_handle) {
                $query->where('user_handle',  $user_handle);
            });
        }

        if ($request->has('user_handle') && $request->user_handle != '') {
            $user_handle = $request->user_handle;
            $submissions->whereHas('owner', function ($query) use ($user_handle) {
                $query->where('user_handle',  $user_handle);
            });
        }

        if ($request->has('problem_title') && $request->problem_title != '') {
            $submissions->whereHas('problem', function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->problem_title . '%');
            });
        }

        if ($request->has('result') && $request->result != '') {
            $submissions->where('result', $request->result);
        }

        if ($request->has('language') && $request->language != '') {
            $submissions->where('language', $request->language);
        }

        // Eager load problem and owner, but allow null values
        $submissions = $submissions->with(['problem', 'owner'])->get()->map(function ($submission) {
            // Ensure problem and owner are always present, even if null
            $submission->problem = $submission->problem ?? null;
            $submission->owner = $submission->owner ?? null;
            return $submission;
        });

        return Inertia::render('submissions/Index', [
            'submissions' => $submissions,
            'filters' => [
                'problem_title' => $request->get('problem_title', ''),
                'user_handle' => $request->get('user_handle', ''),
                'result' => $request->get('result', ''),
                'language' => $request->get('language', ''),
                'my_submissions_only' => $request->boolean('my_submissions_only'),
            ],
        ]);
    }

    public function index_by_problem($problem_handle)
    {

        $submissions = Submission::query();
        if (isset($problem_handle)) {
            $submissions->whereHas('problem', function ($query) use ($problem_handle) {
                $query->where('problem_handle',  $problem_handle);
            });
        }

        $user = Auth::user();
        if (isset($user)) {
            $user_handle = $user->user_handle;
            $submissions->whereHas('owner', function ($query) use ($user_handle) {
                $query->where('user_handle',  $user_handle);
            });
        }

        $submissions = $submissions->with('problem')->get();
        return view('submissions.index_by_problem', compact('submissions'));
    }

    public function general_create()
    {
        return Inertia::render('submissions/Create');
    }

    public function create($problem_handle)
    {
        return view('submissions.create', compact('problem_handle'));
    }

    public function general_store(Request $request)
    {
        $validated = $request->validate([
            'problem_handle' => 'required|string',
            'code' => 'required|string',
            'language' => 'required|in:cpp,java,python',
        ]);

        $problem_handle = $validated['problem_handle'];
        if (!Problem::where('problem_handle', $problem_handle)->exists()) {
            dd("Problem Handle : Problem does NOt exists in the database"); // FOR TESTING
            return back()->withErrors(['problem-url' => 'Problem does NOt exists in the database.']);
        }

        return $this->store($request, app(ProblemSubmitterService::class), $problem_handle);
    }
    // Store a new submission
    public function store(Request $request, ProblemSubmitterService $submitter, $problem_handle)
    {

        $validated = $request->validate([
            'code' => 'required|string',
            'language' => 'required|in:cpp,java,python',
        ]);

        // Find problem by handle
        $problem = Problem::where('problem_handle', $problem_handle)->firstOrFail();

        // Authenticated user
        $user = Auth::user();

        // Get submitting parameter
        $url = $problem->link;
        $code = $validated['code'];
        $language = $validated['language'];

        // Submitting solution (sending request to the service)
        $response = $submitter->fetchOnlineJudgeResponse($url, $code, $language);
        // and return the online judge response (scraped data)
        // in this JSON form 
        /*
        {
        result,
            online_judge_response = str(oj_response),
            original_submission_link = submission_link
        }
        */


        // Check structure
        if (
            !$response ||
            !isset($response['online_judge_response']) ||
            !isset($response['original_submission_link'])
        ) {
            dd(["failed", $response, $url, $code, $language]); // FOR TESTING // 
            return back()->withErrors(['error' => 'Invalid response structure from Online Judge service.']);
        }
        // Store response data
        // Clean up or normalize response

        $online_judge_response = strtolower($response["online_judge_response"]);
        $original_submission_link = $response["original_submission_link"];

        // Create new row in the Database 
        $submission = Submission::create([
            'code' => $validated["code"],
            'language' => $validated["language"],
            'result' => $response['result'],
            'oj_response' => $online_judge_response,
            'original_link' => $original_submission_link ?? null,
            'ai_response' => null,
            'owner_id' => $user->user_handle,
            'problem_id' => $problem->id,
        ]);

        return redirect()->route('submissions.show', $submission->id);
    }

    // Show a single submission
    public function show($id)
    {
        $submission = Submission::with(['owner', 'problem'])->findOrFail($id);
        return view('submissions.show', compact('submission'));
    }

    // Update a submission (refresh it / re-scrape it)
    public function refresh($id, ProblemSubmitterService $submitter)
    {
        // Find the submission or fail
        $submission = Submission::with('problem')->findOrFail($id);

        $problem = $submission->problem;

        // Extract data
        $url = $problem->link;
        $code = $submission->code;
        $language = $submission->language;

        // Re-Scrape (NEEDED : update the python code)
        // $response = $submitter->fetchOnlineJudgeResponse($url, $code, $language);

        if (
            !$response ||
            !isset($response['online_judge_response']) ||
            !isset($response['original_submission_link'])
        ) {
            return back()->withErrors(['error' => 'Failed to refresh. Invalid response from Online Judge.']);
        }

        // Update the submission
        $submission->update([
            'result' => $response['online_judge_response'],
            'original_link' => $response['original_submission_link'],
        ]);

        return redirect()->route('submissions.show', $submission->id)
            ->with('success', 'Submission result refreshed successfully.');
    }

    public function generateAiHint($id, OllamaHintService $ollama)
    {
        $submission = Submission::findOrFail($id);
        $code = $submission->code;
    
        try {
            $hint = $ollama->getDebugHint($code);
    
            \Log::info('Hint from Ollama:', ['hint' => $hint]);
    
            $submission->ai_response = $hint;
            $submission->save();
    
            \Log::info('Updated Submission:', ['id' => $submission->id, 'ai_response' => $submission->ai_response]);
    
            return redirect()->route('submissions.show', $submission->id)
                ->with('success', 'AI hint generated and saved successfully.');
        } catch (\Exception $e) {
            \Log::error('AI hint generation failed', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Failed to generate AI hint.']);
        }
    }
    
    
}
