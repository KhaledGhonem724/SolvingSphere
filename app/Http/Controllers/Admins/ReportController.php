<?php

namespace App\Http\Controllers\Admins;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckAuthority;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;

use App\Models\Report;
use App\Models\Task;
use App\Models\Authority;
use App\Enums\ReportStatus;
use App\Enums\ReportType;

use App\Helpers\ReportHelper;

class ReportController extends Controller
{
    // Public method // DONE
    public function create(Request $request)
    {
        // Optional pre-fill if coming from a reportable entity like a blog
        $reportableType = $request->query('type'); // e.g., 'blog'
        $reportableId = $request->query('id');
    
        return view('admins.public.reports.create', compact('reportableType', 'reportableId'));
    }
    
    // Public method // DONE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(Report::REPORT_TYPES)],
            'message' => 'nullable|string|max:2000',
            'reportable_type' => ['nullable','string', Rule::in(Report::REPORTABLE_TYPES)],
            'reportable_id' => 'nullable|integer',
        ]);

        $reportableClass = ReportHelper::resolveReportableClass($validated['reportable_type'] ?? '');
        // if the helper returned NULL for any reason
        if (($validated['reportable_type'] ?? null)  && !$reportableClass) {
            return redirect()->back()->withErrors(['reportable_type' => 'Invalid reportable type specified.']);
        }

        Report::create([
            'user_id' => $request->user()->user_handle,
            'type' => $validated['type'],
            'message' => $validated['message'],
            'status' => 'new',
            'reportable_type' => $reportableClass,
            'reportable_id' => $validated['reportable_id'] ?? null,
        ]);
        /*
        return {
            'user_id' : $request->user()->user_handle,
            'type' : $validated['type'],
            'message' : $validated['message'],
            'status' : 'new',
            'reportable_type' : $reportableClass,
            'reportable_id' : $validated['reportable_id'] ?? null,
        };
        */
        // return response()->json($request->all());
        return redirect()->route('dashboard')->with('success', 'Your report has been submitted.');
    }

    // Staff-only
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'reportable']);
        $statuses = ReportStatus::cases();
        $types = ReportType::cases();

        // Optional filtering
        if ($request->filled('status') && ReportStatus::tryFrom($request->status)) {
            $query->ofStatus(ReportStatus::from($request->status));
        }

        if ($request->filled('type') && ReportType::tryFrom($request->type)) {
            $query->ofType(ReportType::from($request->type));
        }

        if ($request->filled('user')) {
            $query->byUser($request->user);
        }

        $reports = $query->latest()->paginate(15)->withQueryString();

        return view('admins.staff.reports.index', compact(['reports','statuses','types']));
    }

    // Staff-only
    public function show(string $id)
    {
        $report = Report::with(['reporter', 'reportable'])->findOrFail($id);
    
        return view('admins.staff.reports.show', compact('report'));
    }
    public function markReviewed($report_id)
    {
        $report = Report::findOrFail($report_id);
        $report->status = ReportStatus::REVIEWED;
        $report->save();
    
        return back()->with('success', 'Report marked as reviewed.');
    }
    
    public function markIgnored($report_id)
    {
        $report = Report::findOrFail($report_id);
        $report->status = ReportStatus::IGNORED;
        $report->save();
    
        return back()->with('success', 'Report marked as ignored.');
    }
    // Staff-only
    public function createTask($report_id)
    {
        $report = Report::findOrFail($report_id);
        $authorities = Authority::all(); // or filtered based on user role
        return view('admins.staff.reports.create_task', compact('report', 'authorities'));
    }

}