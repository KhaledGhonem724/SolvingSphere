<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use App\Models\Roadmap;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class RoadmapController extends Controller
{
    public function index()
    {
        $authHandle = auth()->user()->user_handle;
        $roadmaps = Roadmap::withCount('topics')
            ->where(function ($query) use ($authHandle) {
                $query->where('visibility', 'public')
                    ->orWhere('owner_id', $authHandle);
            })
            ->get();

        return Inertia::render('Containers/Roadmaps', [
            'roadmaps' => $roadmaps,
            'authHandle' => $authHandle,
        ]);
    }

    public function create()
    {
        return Inertia::render('Containers/CreateRoadmap');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
        ]);

        $roadmap = Roadmap::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'visibility' => $validated['visibility'],
            'owner_id' => auth()->user()->user_handle,
            'share_token' => $validated['visibility'] === 'private' ? Str::random(32) : null,
        ]);

        return redirect()->route('roadmaps.index')->with('success', 'Roadmap created successfully.');
    }
    public function shared($token)
    {
        $roadmap = Roadmap::where('share_token', $token)
            ->with('topics')
            ->firstOrFail();

        return Inertia::render('Containers/RoadmapShow', ['roadmap' => $roadmap]);
    }

    public function show($id)
    {
        $roadmap = Roadmap::with('topics')->findOrFail($id);

        if ($roadmap->visibility === 'public' || $roadmap->owner_id === auth()->user()->user_handle) {
            return Inertia::render('Containers/RoadmapShow', ['roadmap' => $roadmap]);
        }

        abort(403, 'You do not have permission to view this roadmap.');
    }

    public function destroy(Roadmap $roadmap)
    {
        if ($roadmap->owner_id !== auth()->user()->user_handle) {
            abort(403);
        }

        $roadmap->delete();

        return redirect()->route('roadmaps.index')->with('success', 'Roadmap deleted successfully.');
    }
    public function addTopics(Request $request)
    {
        $validated = $request->validate([
            'roadmap_id' => 'required|exists:roadmaps,id',
            'topics' => 'required|array|min:1',
            'topics.*.id' => 'required|integer|exists:topics,id',
        ]);

        $roadmap = Roadmap::findOrFail($validated['roadmap_id']);

        if (!auth()->check() || $roadmap->owner_id !== auth()->user()->user_handle) {
            abort(403);
        }

        // Get already attached topic IDs
        $existingTopicIds = $roadmap->topics()->pluck('topics.id')->toArray();

        $attachData = [];
        foreach ($validated['topics'] as $index => $topic) {
            if (!in_array($topic['id'], $existingTopicIds)) {
                $attachData[$topic['id']] = ['order' => $index + 1];
            }
        }

        if (!empty($attachData)) {
            $roadmap->topics()->attach($attachData);
        }

        return redirect()->route('roadmaps.show', $roadmap->id)
            ->with('success', 'Topics added to roadmap successfully.');
    }




    public function addTopicsView(Roadmap $roadmap)
    {
        if ($roadmap->owner_id !== auth()->user()->user_handle) {
            abort(403);
        }

        $topics = Topic::where(function ($query) {
            $query->where('visibility', 'public');

            if (auth()->check()) {
                $query->orWhere('owner_id', auth()->user()->user_handle);
            }
        })->get();
        return Inertia::render('Containers/AddRoadmapTopics', [
            'roadmap' => $roadmap,
            'topics' => $topics,
        ]);
    }
    public function removeTopic(Request $request, Roadmap $roadmap, $topicId)
    {
        if ($roadmap->owner_id !== auth()->user()->user_handle) {
            abort(403);
        }

        // Optional: check if the topic is attached before detaching
        if (!$roadmap->topics()->where('topics.id', $topicId)->exists()) {
            return response()->json(['message' => 'Topic not found in roadmap.'], 404);
        }

        $roadmap->topics()->detach($topicId);

        return back()->with('success', 'Topic removed.');
    }
}
