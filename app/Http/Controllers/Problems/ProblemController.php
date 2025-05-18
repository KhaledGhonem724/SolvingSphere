<?php

namespace App\Http\Controllers\Problems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;

class ProblemController extends Controller
{
    public function index()
    {
        $problems = Problem::all();
        return view('problems.index', compact('problems'));
    }
    
    public function create()
    {
        return view('problems.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            // Add other fields as necessary
        ]);
    
        Problem::create($validated);
    
        return redirect()->route('problems.index')->with('success', 'Problem created successfully.');
    }

    public function show(string $problem_handle)
    {
        $problem = Problem::findOrFail($problem_handle);
        return view('problems.show', compact('problem'));
/*
        return view('problems.show', [
            'title' => $problem->title,
            'problemName' => $problem->title,
            'link' => $problem->link,
            'website' => $problem->website,
            'timelimit' => $problem->timelimit,
            'memorylimit' => $problem->memorylimit,
            'statement' => $problem->statement,
            'testcases' => $problem->testcases,
            'notes' => $problem->notes,
        ]);

*/
    }
}
