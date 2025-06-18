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
    public function index()
    {
        $tasks = Task::latest()->paginate(20);
        return view('admins.staff.tasks.index', compact('tasks'));
    }

    public function show($task_id)
    {
        $task = Task::findOrFail($task_id);
        return view('admins.staff.tasks.show', compact('task'));
    }

    public function store(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'user_id' => ['nullable', 'exists:users,user_handle'],
            'authority_id' => ['required', 'exists:authorities,id'],
            'report_id' => ['nullable', 'exists:reports,id'],
            'type' => ['required', Rule::in(['scientific', 'ethical', 'technical', 'other'])],
            'user_note' => ['nullable', 'string'],
            'manager_note' => ['nullable', 'string'],
            'assignee_id' => ['nullable', 'exists:users,user_handle'],
        ]);

        // ✅ Create task
        $task = Task::create([
            'title' => $validated['title'] ?? null,
            'user_id' => $validated['user_id'],
            'authority_id' => $validated['authority_id'],
            'report_id' => $validated['report_id'] ?? null,
            'type' => $validated['type'],
            'user_note' => $validated['user_note'] ?? null,
            'manager_note' => $validated['manager_note'] ?? null,
            'admin_note' => null, // could be filled later
            'assignee_id' => $validated['assignee_id']?? null,
            'status' => 'free', // default is 'free', but we assign directly
            'reportable_type' => null,
            'reportable_id' => null,
        ]);

        // ✅ Redirect back to report or tasks page
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

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'status' => ['required', new Enum(TaskStatus::class)],
            'assignee_id' => ['nullable', 'exists:users,user_handle'],
            'authority_id' => ['nullable', 'exists:authorities,id'],
            'user_note' => ['nullable', 'string'],
            'manager_note' => ['nullable', 'string'],
            'admin_note' => ['nullable', 'string'],
        ]);

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
}