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
use App\Enums\TaskStatus;
use App\Models\User;

use Illuminate\Validation\Rules\Enum;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::query();
        $authorities = Authority::all();
    
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }

        if ($request->filled('authority_id')) {
            $query->where('authority_id', $request->authority_id);
        }

        $tasks = $query->with(['assignee', 'authority'])->paginate(10);

        return view('admins.staff.tasks.index', [
            'tasks' => $tasks,
            'statuses' => TaskStatus::cases(),
            'types' => ReportType::cases(),
            'authorities' => $authorities,
            'request' => $request,
        ]);
    }

    public function byAuthority(Authority $authority)
    {
        $tasks = Task::where('authority_id', $authority->id)
            ->with(['assignee', 'reporter']) // preload relations if needed
            ->latest()
            ->paginate(10);

        return view('admins.staff.tasks.by_authority', [
            'authority' => $authority,
            'tasks' => $tasks,
        ]);
    }
    public function create()
    {
        $authorities = Authority::all();
        $users = User::where('status', 'admin')->get(); // all admin users
        $types = ReportType::cases();
    
        return view('admins.staff.tasks.create', compact('authorities', 'users', 'types'));
    }

    public function show($task_id)
    {
        $task = Task::findOrFail($task_id);
        return view('admins.staff.tasks.show', compact('task'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,user_handle'],
            'authority_id' => ['required', 'exists:authorities,id'],
            'report_id' => ['nullable', 'exists:reports,id'],
            'type' => ['required', Rule::in(ReportType::casesEnumValues())],
            'user_note' => ['nullable', 'string'],
            'manager_note' => ['nullable', 'string'],
            'assignee_id' => ['nullable', 'exists:users,user_handle'],
        ]);


        // Mark the related report as "reviewed" if provided
        if (!empty($validated['report_id'])) {
            $report = Report::find($validated['report_id']);
            if ($report && !$report->status->isFinal()) {
                $report->status = ReportStatus::REVIEWED;
                $report->save();
            }
        }
        $task = Task::create([
            'title'           => $validated['title'] ?? null,
            'user_id'         => $validated['user_id'] ,
            'authority_id'    => $validated['authority_id'],
            'report_id'       => $validated['report_id'] ?? null,
            'type'            => ReportType::from($validated['type']),
            'user_note'       => $validated['user_note'] ?? null,
            'manager_note'    => $validated['manager_note'] ?? null,
            'admin_note'      => null,
            'assignee_id'     => $validated['assignee_id'] ?? null,
            'status'          => $validated['assignee_id']
                                    ? TaskStatus::Assigned
                                    : TaskStatus::Free,
            'reportable_type' => null,
            'reportable_id'   => null,
        ]);

        return redirect()
            ->route('staff.tasks.show', $task->id)
            ->with('success', 'Task has been created successfully.');
    }
    
    public function edit($task_id)
    {
        $task = Task::findOrFail($task_id);
        $authorities = Authority::all(); // for dropdowns
        $users = User::all(); // for assigning

        return view('admins.staff.tasks.edit', compact('task', 'authorities', 'users'));
    }

    public function update(Request $request, $task_id)
    {
        $task = Task::findOrFail($task_id);


        $user = auth()->user();

        // Lock edits if task is solved or dismissed and user is NOT a manager
        if (in_array($task->status, [TaskStatus::Solved, TaskStatus::Dismissed])
            && !$user->hasAuthority('manage_tasks')) {
            return redirect()
                ->back()
                ->withErrors(['You are not allowed to edit a solved or dismissed task.']);
        }

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', new Enum(TaskStatus::class)],
            'assignee_id' => ['nullable', 'exists:users,user_handle'],
            'authority_id' => ['nullable', 'exists:authorities,id'],
            'user_note' => ['nullable', 'string'],
            'manager_note' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
        ]);
        // Convert status to enum instance
        $status = TaskStatus::from($validated['status']);

        // If task is being reset to free , it should not have an assignee
        if (in_array($status, [TaskStatus::Free])) {
            $validated['assignee_id'] = null;
        }

        // Apply status back to the validated data
        $validated['status'] = $status;
        $task->update($validated);

        return redirect()->route('staff.tasks.show', $task->id)
            ->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request,  $task_id,  $status)
    {
        $task = Task::findOrFail($task_id);
        try {
            $statusEnum = TaskStatus::from($status);
        } catch (\ValueError $e) {
            abort(400, 'Invalid status value: ' . $status);
        }

        $user = $request->user();
        $authority_name = Authority::findOrFail($task->authority_id)->name;
        // ✅ Role/Authority-based logic
        if ($statusEnum === TaskStatus::Assigned) {
            if ($task->assignee_id == null && $user->hasAuthority($authority_name)) {
                $task->assignee_id = $user->user_handle;
                $task->status = TaskStatus::Assigned;
            } else {
                dd([$user->hasAuthority($task->authority_id),$task->authority_id]);
                abort(403, 'You cannot accept this task.: ');
            }
        } elseif ($statusEnum === TaskStatus::Refused) {
            if ($task->assignee_id === $user->user_handle) {
                $task->assignee_id = null;
                $task->status = TaskStatus::Refused;
            } else {
                abort(403, 'Only the assignee can decline the task.');
            }
        } elseif ($statusEnum === TaskStatus::Solved) {
            if ($task->assignee_id === $user->user_handle) {
                $task->status = TaskStatus::Solved;
            } else {
                abort(403, 'Only the assignee can mark the task as solved.');
            }
        } elseif ($statusEnum === TaskStatus::Dismissed) {
            if ($task->assignee_id === $user->user_handle) {
                $task->status = TaskStatus::Dismissed;
            } else {
                abort(403, 'Only the assignee can dismiss the task.');
            }
        } else {
            abort(400, 'Unsupported status update.');
        }

        $task->save();

        return redirect()->route('staff.tasks.show', $task->id)->with('success', 'Task status updated.');
    }
    
    public function addAdminNote(Request $request, $task_id)
    {
        $task = Task::findOrFail($task_id);
    
        $request->validate([
            'admin_note' => ['required', 'string'],
        ]);
    
        $task->admin_note = $request->admin_note;
        $task->save();
    
        return back()->with('success', 'Admin note added.');
    }
    
    public function userTasks($user_handle)
    {
        $tasks = Task::where('user_id', $user_handle)->latest()->paginate(20);
        return view('admins.staff.tasks.index', compact('tasks'));
    }

    public function myTasks(Request $request)
    {
        $user = $request->user();
        $tasks = Task::where('assignee_id', $user->user_handle)->latest()->paginate(20);
        return view('admins.staff.tasks.index', compact('tasks'));
    }

    public function authorityTasks($auth_id)
    {
        $tasks = Task::where('authority_id', $auth_id)->latest()->paginate(20);
        return view('admins.staff.tasks.index', compact('tasks'));
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
    
        return redirect()->route('staff.tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
    

}