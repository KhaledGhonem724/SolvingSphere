<?php

namespace App\Http\Controllers\Problems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;

use App\Services\ProblemScraperService;


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


    public function store(Request $request, ProblemScraperService $scraper)
    {
        // Validate the input
        $validated = $request->validate([
            'problem-url' => 'required|url',
        ]);

        $url = $validated['problem-url'];
        if (Problem::where('link', $url)->exists()){
            return back()->withErrors(['problem-url' => 'Problem already exists in the database.']);
        }
        // Scrape the problem
        $scraped = $scraper->fetchProblemData($url);

        // Check structure
        if (
            !$scraped ||
            $scraped['status'] !== 'scraped' ||
            !isset($scraped['problem']['problem_handle'])
        ) {
            return back()->withErrors(['problem-url' => 'Failed to fetch problem data.']);
        }

        // Store scraped data
        $problemData = $scraped['problem'];
        $problemHandle = $problemData['problem_handle'];

        // Check for duplicates
        if (Problem::where('problem_handle', $problemHandle)->exists()) {
            return back()->withErrors(['problem-url' => 'Problem already exists in the database.']);
        }

        // Save the new problem
        $problem = Problem::create([
            'problem_handle' => $problemHandle,
            'link'           => $problemData['link'],
            'website'        => $problemData['website'] ?? null,
            'title'          => $problemData['title'],
            'timelimit'      => $problemData['timelimit'],
            'memorylimit'    => $problemData['memorylimit'],
            'statement'      => $problemData['statement'],
            'testcases'      => json_encode($problemData['testcases']), // NEEDED // review this 
            'notes'          => $problemData['notes'] ?? null,
        ]);

        // NEEDED // Save tags if your model supports it (e.g., via taggable or pivot table)
        // Example: $problem->tags()->createMany($scraped['tags']);

        return redirect()->route('problems.show', $problemHandle)
                        ->with('success', 'Problem created successfully.');

        // return redirect()->route('problems.index')->with('success', 'Problem created successfully.');

    }


    public function show(string $problem_handle)
    {
        $problem = Problem::findOrFail($problem_handle);
        return view('problems.show', compact('problem'));
    }
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
