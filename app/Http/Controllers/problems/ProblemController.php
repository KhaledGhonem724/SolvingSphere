<?php

namespace App\Http\Controllers;

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
    
    public function show(string $id)
    {
        $problem = Problem::findOrFail($id);
        return view('problems.show', compact('problem'));
    }
}
