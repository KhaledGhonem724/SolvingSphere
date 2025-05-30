<?php

namespace App\Http\Controllers\Submissions;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Submission;
use App\Models\Problem;


use App\Services\ProblemSubmitterService;

//use App\Http\Controllers\Problems\ProblemController;

class SubmissionController extends Controller
{
    // List all submissions
    public function index()
    {
        $submissions = Submission::all();
        return view('submissions.index', compact('submissions'));
        // return Submission::with(['owner', 'problem'])->get();
    }

    public function create($problem_handle)
    {
        return view('submissions.create', compact('problem_handle'));
    }

    // Store a new submission
    public function store(Request $request,ProblemSubmitterService $submitter, $problem_handle)
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
        $response = $submitter->fetchOnlineJudgeResponse($url,$code,$language);
        // and return the online judge response (scraped data)
        // in this JSON form 
        /*
        {
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
            dd(["failed",$response,$url,$code,$language]); // FOR TESTING // 
            return back()->withErrors(['error' => 'Invalid response structure from Online Judge service.']); 

        }
        dd(["GOOD",$response,$url,$code,$language]);

        // Store response data
        $online_judge_response = $response["online_judge_response"];
        $original_submission_link = $response["original_submission_link"];
        
        // dd(["Still Good",$original_submission_link]);

        // Create new row in the Database 
        $submission = Submission::create([
            'code' => $validated["code"],
            'language' => $validated["language"],
            'result' => $online_judge_response ,
            'original_link' => $original_submission_link ?? null,
            'ai_response' => null,
            'owner_id' => $user->user_handle,
            'problem_id' => $problem->problem_handle,
        ]);



        return redirect()->route('submissions.show', $submission->id)
        ->with('success', 'Problem created successfully.');
    }

    // Show a single submission
    public function show($id)
    {
        // $submission = Submission::with(['owner', 'problem'])->findOrFail($id);
        $submission = Submission::findOrFail($id);
        return view('submissions.show', compact('submission'));
    }

    // Update a submission
    public function update(Request $request, $id)
    {
        $submission = Submission::findOrFail($id);

        $validated = $request->validate([
            'code' => 'sometimes|string',
            'language' => 'sometimes|in:cpp,java,python',
            'result' => 'sometimes|string',
            'original_link' => 'nullable|url',
            'ai_response' => 'nullable|string',
            'owner_id' => 'sometimes|exists:users,user_handle',
            'problem_id' => 'sometimes|exists:problems,problem_handle',
        ]);

        $submission->update($validated);

        return view('submissions.show', compact('submission'));
    }

    // Delete a submission
    public function destroy($id)
    {
        $submission = Submission::findOrFail($id);
        $submission->delete();

        return redirect()->route('submissions.index');
    }
}

