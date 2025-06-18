<?php

namespace App\Http\Controllers\Containers;

use App\Http\Controllers\Controller;
use App\Models\Problem;
use App\Models\Sheet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SheetController extends Controller
{
    public function index()
    {
        $sheets = Sheet::withCount('problems')
            ->where(function ($query) {
                $query->where('visibility', 'public')
                    ->orWhere('owner_id', auth()->user()->user_handle);
            })
            ->get();

        return Inertia::render('Containers/sheet', [
            'sheets' => $sheets,
        ]);
    }

    public function create()
    {
        return Inertia::render('Containers/create_sheet');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'visibility' => 'required|in:public,private',
        ]);

        Sheet::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'visibility' => $validated['visibility'],
            'owner_id' => auth()->user()->user_handle,
            'share_token' => $validated['visibility'] === 'private' ? Str::uuid() : null,
        ]);

        return redirect()->route('sheet.index')->with('success', 'Sheet created successfully.');
    }
    public function add_problem (Request $request,Sheet $sheet){
        $sheet->problems()->sync($request->problems);
        return 'attached';
    }
    public function post (Request $request,Sheet $sheet){
        return $sheet->load('problems');
    }

    public function show($id)
    {
        $sheet = Sheet::with('problems')->findOrFail($id);

        if ($sheet->visibility === 'public' || $sheet->owner_id === auth()->user()->user_handle) {
            return Inertia::render('Containers/SheetShow', ['sheet' => $sheet]);
        }

        abort(403, 'You do not have permission to view this sheet.');
    }

    public function shared($token)
    {
        $sheet = Sheet::where('share_token', $token)->with('problems')->firstOrFail();

        return Inertia::render('Containers/SheetShow', ['sheet' => $sheet]);
    }
}
