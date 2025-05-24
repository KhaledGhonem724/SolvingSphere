<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    // List all submissions
    public function index()
    {
        $submissions = Submission::all();
        return view('submissions.index', compact('submissions'));
        // return Submission::with(['owner', 'problem'])->get();
    }

    public function create()
    {
        return view('submissions.create');
    }

    // Store a new submission
    public function store(Request $request,ProblemSubmitterService $submitter)
    {

        $validated = $request->validate([
            'code' => 'required|string',
            'language' => 'required|in:cpp,java,python',
            'owner_id' => 'required|exists:users,user_handle',
            'problem_id' => 'required|exists:problems,problem_handle',
        ]);
        

        // Get submitting parameter
        $url = "";
        $code = validated['code'];
        $language = validated['language'];

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
            return back()->withErrors(['error' => 'Invalid response structure from Online Judge service.']); 

        }

        // Store response data
        $online_judge_response = $response["online_judge_response"];
        $original_submission_link = $response["original_submission_link"];

        // Create new row in the Database 
        $submission = Submission::create([
            'code' => validated["code"],
            'language' => validated["language"],
            'result' => $online_judge_response ,
            'original_link' => $original_submission_link ?? null,
            'ai_response' => null,
            'owner_id' => validated["owner_id"],
            'problem_id' => validated["problem_id"],
        ]);

        return redirect()->route('submissions.show', compact('submission'))
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

        return index();
    }
}
