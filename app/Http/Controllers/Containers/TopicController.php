<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class TopicController extends Controller
{
    public function index()
    {
        $authHandle = auth()->user()->user_handle;
        $topics = Topic::withCount('problems')
            ->where(function ($query) use ($authHandle) {
                $query->where('visibility', 'public')
                    ->orWhere('owner_id', $authHandle);
            })
            ->get();

        return Inertia::render('Containers/Topics', [
            'topics' => $topics,
            'authHandle' => $authHandle,
        ]);
    }

    public function create()
    {
        return Inertia::render('Containers/CreateTopic');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
        ]);

        $topic = Topic::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'visibility' => $validated['visibility'],
            'owner_id' => auth()->user()->user_handle,
            'share_token' => $validated['visibility'] === 'private' ? Str::random(32) : null,
        ]);

        return redirect()->route('topics.index')->with('success', 'Topic created successfully.');
    }


    public function show($id)
    {
        $topic = Topic::with(['problems'])->findOrFail($id);

        if ($topic->visibility === 'public' || $topic->owner_id === auth()->user()->user_handle) {
            return Inertia::render('Containers/TopicShow', ['topic' => $topic]);
        }

        abort(403, 'You do not have permission to view this topic.');
    }

    public function destroy(Topic $topic)
    {
        if ($topic->owner_id !== auth()->user()->user_handle) {
            abort(403);
        }

        $topic->delete();

        return redirect()->route('topics.index')->with('success', 'Topic deleted successfully.');
    }

    public function add_problem(Request $request)
    {
        $validated = $request->validate([
            'topic_id' => 'required|exists:topics,id',
            'problems' => 'required|array',
            'problems.*.handle' => 'required|string|exists:problems,problem_handle',
            'problems.*.external_link' => 'nullable|string|url',
        ]);

        $topic = Topic::findOrFail($validated['topic_id']);

        $existingProblemIds = $topic->problems()->pluck('problems.id')->toArray();

        $newProblems = [];
        $alreadyInTopic = [];

        foreach ($validated['problems'] as $item) {
            $problem = Problem::where('problem_handle', $item['handle'])->first();

            if (!$problem) continue;

            if (in_array($problem->id, $existingProblemIds)) {
                $alreadyInTopic[] = $problem->problem_handle;
            } else {
                $newProblems[$problem->id] = [
                    'external_link' => $item['external_link'] ?? null,
                ];
            }
        }

        if (!empty($newProblems)) {
            $topic->problems()->attach($newProblems);
        }

        return redirect()->route('topics.show', $topic->id)->with([
            'success' => !empty($newProblems) ? 'تمت إضافة المشاكل الجديدة بنجاح.' : null,
            'warning' => !empty($alreadyInTopic) ? 'بعض المشاكل كانت موجودة بالفعل: ' . implode(', ', $alreadyInTopic) : null,
        ]);
    }



    public function addProblemView(Topic $topic)
    {
        // Only the topic owner can view the add problem form
        if ($topic->owner_id !== auth()->user()->user_handle) {
            abort(403);
        }

        // You can filter or paginate this list if the problem count gets large
        $problems = Problem::all();

        return Inertia::render('Containers/AddTopicProblem', [
            'topic' => $topic,
            'problems' => $problems,
        ]);
    }



    public function post(Request $request, Topic $topic)
    {
        return $topic->load('problems');
    }

    public function removeProblem($topicId, $problemHandle)
    {
        $topic = Topic::findOrFail($topicId);

        if ($topic->owner_id !== auth()->user()->user_handle) {
            abort(403);
        }

        $problem = Problem::where('problem_handle', $problemHandle)->firstOrFail();
        $topic->problems()->detach($problem->id);


        return back()->with('success', 'Problem removed from topic.');
    }

    public function shared($token)
    {
        $topic = Topic::where('share_token', $token)->with('problems')->firstOrFail();

        return Inertia::render('Containers/TopicShow', ['topic' => $topic]);
    }
}
