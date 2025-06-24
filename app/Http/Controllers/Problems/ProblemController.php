<?php

namespace App\Http\Controllers\Problems;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Services\ProblemScraperService;

use App\Models\Problem;
use App\Models\Tag;
use App\Models\Submission;

class ProblemController extends Controller
{
    public function index(Request $request)
    {
        $problems = Problem::query();

        if ($request->filled('title')) {
            $problems->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->has('website') && $request->website !== '') {
            $problems->where('website', $request->website);
        }

        if ($request->filled('tags')) {
            $tags = $request->input('tags', []);
            $matchAll = $request->boolean('match_all_tags');

            if ($matchAll) {
                // Match all selected tags (AND)
                $problems->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('tags.id', $tags);
                }, '=', count($tags));
            } else {
                // Match any selected tag (OR)
                $problems->whereHas('tags', function ($query) use ($tags) {
                    $query->whereIn('tags.id', $tags);
                });
            }
        }

        $allTags = Tag::orderBy('name')->get();
        $user = Auth::user();

        // Paginate first, with tags relationship loaded
        $problems = $problems->with('tags')->latest()->paginate(10)->withQueryString();

        $userStatus = collect();

        if ($user) {
            // Map the best result per problem for this user
            $userStatus = Submission::where('owner_id', $user->user_handle)
                ->select('problem_id', 'result')
                ->get()
                ->groupBy('problem_id')
                ->map(function ($subs) {
                    $priority = ['solved' => 1, 'attempted' => 2, 'todo' => 3];
                    return $subs->sortBy(function ($s) use ($priority) {
                        return $priority[$s->result] ?? 99;
                    })->first();
                });

            // If state filter is applied, filter the paginated problems collection
            if ($request->filled('state') && $request->state !== 'All') {
                $state = $request->state;
                $problems->setCollection(
                    $problems->getCollection()->filter(function ($problem) use ($userStatus, $state) {
                        $status = $userStatus[$problem->problem_handle]->result ?? 'todo';
                        return $status === $state;
                    })->values()
                );
            }

            return Inertia::render('problems/Index', [
                'title' => 'Problems',
                'problems' => $problems,
                'allTags' => $allTags,
                'userSubmissions' => $userStatus,
            ]);
        }

        return Inertia::render('problems/Index', [
            'title' => 'Problems',
            'problems' => $problems,
            'allTags' => $allTags,
        ]);
    }

    
    public function create()
    {
        return Inertia::render('problems/Create');
    }

    public function store(Request $request, ProblemScraperService $scraper)
    {
        // Validate the input
        $validated = $request->validate([
            'problem-url' => 'required|url',
        ]);

        $url = $validated['problem-url'];
        if (Problem::where('link', $url)->exists()){
            dd("URL : Problem already exists in the database"); // FOR TESTING
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
            dd(["FUCK",$scraped]); // FOR TESTING
            return back()->withErrors(['problem-url' => 'Failed to fetch problem data.']);
        }
        /*
        Scrapper response
        {
            'status': 'scraped',
            'problem': 
                {
                    'problem_handle': string,
                    'link': string url ,
                    'website': 'HackerEarth',
                    'title': string,
                    'timelimit': string(number + " Sec"),
                    'memorylimit':  string(number + " MB"),
                    'statement': string,
                    'testcases': string,
                    'notes': string
                },
            'tags': [tag1, tag2, tag3]
        }
        */

        // Store scraped data
        $problemData = $scraped['problem'];
        $problemHandle = $problemData['problem_handle'];
        $tagsArray = $scraped['tags'];
        
        // Check for duplicates
        if (Problem::where('problem_handle', $problemHandle)->exists()) {

            dd("problem_handle : Problem already exists in the database");// FOR TESTING
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

        // Create or get tags
        $tags = collect($tagsArray)->map(function ($name) {
            return Tag::firstOrCreate(['name' => $name])->id;
        });

        // Sync with problem
        $problem->tags()->sync($tags);



        // NEEDED // Save tags if your model supports it (e.g., via taggable or pivot table)
        // Example: $problem->tags()->createMany($scraped['tags']);

        return redirect()->route('problems.show', $problemHandle)
                        ->with('success', 'Problem created successfully.');

        // return redirect()->route('problems.index')->with('success', 'Problem created successfully.');

    }

    public function show(Problem $problem)
    {
        $problem = Problem::with(['tags', 'submissions.owner'])->findOrFail($problem_handle);
        $user = Auth::user();
        
        // Get user's submission status for this problem
        $userSubmission = null;
        if ($user) {
            $userSubmission = Submission::where('owner_id', $user->user_handle)
                ->where('problem_id', $problem_handle)
                ->with('owner')
                ->latest()
                ->first();
        }
        
        return Inertia::render('problems/Show', [
            'problem' => $problem,
            'userSubmission' => $userSubmission,
        ]);
    }

}

