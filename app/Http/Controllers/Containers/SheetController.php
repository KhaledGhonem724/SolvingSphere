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
    // public function add_problem(Request $request, Sheet $sheet)
    // {
    //     $sheet->problems()->sync($request->problems);
    //     return 'sucss';
    // }
    public function add_problem(Request $request)
    {
        $validated = $request->validate([
            'sheet_id' => 'required|exists:sheets,id',
            'problem_handles' => 'required|array',
            'problem_handles.*' => 'required|string|exists:problems,problem_handle',
        ]);

        $sheet = Sheet::findOrFail($validated['sheet_id']);

        // جيب المشاكل الموجودة فعليًا
        $existingProblemIds = $sheet->problems()->pluck('problems.id')->toArray();

        // جيب المشاكل من الـ handles
        $problems = Problem::whereIn('problem_handle', $validated['problem_handles'])->get();

        $newProblems = [];
        $alreadyInSheet = [];

        foreach ($problems as $problem) {
            if (in_array($problem->id, $existingProblemIds)) {
                $alreadyInSheet[] = $problem->problem_handle;
            } else {
                $newProblems[] = $problem->id;
            }
        }

        if (!empty($newProblems)) {
            $sheet->problems()->attach($newProblems);
        }

        return redirect()->route('sheet.show', $sheet->id)->with([
            'success' => !empty($newProblems) ? 'تمت إضافة المشاكل الجديدة بنجاح.' : null,
            'warning' => !empty($alreadyInSheet) ? 'بعض المشاكل كانت موجودة بالفعل: ' . implode(', ', $alreadyInSheet) : null,
        ]);
    }


    public function post(Request $request, Sheet $sheet)
    {
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
    
    public function removeProblem($sheetId, $problemId)
    {
        $sheet = Sheet::findOrFail($sheetId);
        $problem = Problem::findOrFail($problemId);

        if ($sheet->owner_id !== auth()->user()->user_handle) {
            abort(403, 'Unauthorized');
        }

        $sheet->problems()->detach($problem->id);

        return back()->with('success', 'Problem removed from sheet.');
    }


    public function shared($token)
    {
        $sheet = Sheet::where('share_token', $token)->with('problems')->firstOrFail();

        return Inertia::render('Containers/SheetShow', ['sheet' => $sheet]);
    }
}
