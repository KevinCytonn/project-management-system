<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks()
            ->with(['assignee:id,name,email,role_id', 'deliverables'])
            ->get();

        return Inertia::render('Tasks/TaskList', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    public function myTasks(Request $request )
    {
        $user = $request->user();

         $tasks = Task::with(['project', 'deliverables'])
    ->where('assigned_to', $user->id)
    ->get();

        $summary = [
            'total' => $tasks->count(),
            'not_started' => $tasks->where('status', 'not_started')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'testing' => $tasks->where('status', 'testing')->count(),
            'complete' => $tasks->where('status', 'complete')->count(),
            'overdue' => $tasks->filter(fn($t) => $t->due_date && $t->due_date->isPast() && $t->status !== 'complete')->count(),
        ];

        return Inertia::render('Tasks/MyTasks', [
            'tasks' => $tasks,
            'summary' => $summary,
        ]);
    }

    public function create(Project $project, Request $request)
    {
        $user = $request->user();

       
        if ($user->role->name !== 'product_manager' && $user->role->name !== 'development_manager' && $user->role->name!=='design_manager') {
            abort(403);
        }

        $users = User::visibleUsers($user);

        return Inertia::render('Tasks/TaskUpload', [
            'project' => $project,
            'members' => $users,
        ]);
    }

    public function store(Request $request, Project $project)
{
    $user = $request->user();

    
    if (!in_array($user->role->name, ['product_manager', 'development_manager', 'design_manager'])) {
        abort(403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'assigned_to' => 'nullable|exists:users,id',
        'stage' => ['required', Rule::in(['product','design','development'])],
        'status' => ['required', Rule::in(Task::allowedStatusesForStage(request('stage')))],
        'due_date' => 'nullable|date',
    ]);

    // Stage-locking rules
    if ($validated['stage'] === 'design' && !in_array($project->current_stage, ['design','development','completed'])) {
        return back()->withErrors(['stage' => 'Design tasks are locked until the product stage completes.']);
    }

    if ($validated['stage'] === 'development' && !in_array($project->current_stage, ['development','completed'])) {
        return back()->withErrors(['stage' => 'Development tasks are locked until previous stages complete.']);
    }

    $task = Task::create([
        'project_id' => $project->id,
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'assigned_to' => $validated['assigned_to'] ?? null,
        'stage' => $validated['stage'],
        'status' => $validated['status'],
        'due_date' => $validated['due_date'] ?? null,
    ]);

    if ($task->assigned_to) {
        Notification::create([
            'user_id' => $task->assigned_to,
            'project_id' => $project->id,
            'task_id' => $task->id,
            'type' => Notification::TYPE_TASK_ASSIGNED,
            'message' => "You have been assigned to task '{$task->title}' in project '{$project->name}'.",
            'is_read' => false,
        ]);
    }

    $project->updateStage();

    return redirect()->route('tasks.index', $project->id)->with('success', 'Task created successfully.');
}

    public function edit(Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) abort(404);

        $members = User::all();

        return Inertia::render('Tasks/EditTask', [
            'project' => $project,
            'task' => $task,
            'members' => $members,
        ]);
    }

    public function update(Request $request, Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) abort(404);

       
        if ($request->has('status') && $request->except('status') === []) {
            $validated = $request->validate([
                'status' => ['required', Rule::in(Task::allowedStatusesForStage($task->stage))],
            ]);

            $task->update(['status' => $validated['status']]);
            $project->updateStage();

            return back()->with('success', 'Task status updated.');
        }

       
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(Task::allowedStatusesForStage($task->stage))],
            'due_date' => 'nullable|date',
            'stage' => ['sometimes', Rule::in(['product','design','development'])],
        ]);

        if (isset($validated['stage'])) {
            if ($validated['stage'] === 'design' && !in_array($project->current_stage, ['design','development','completed'])) {
                return back()->withErrors(['stage' => 'Design tasks are locked until the product stage completes.']);
            }
            if ($validated['stage'] === 'development' && !in_array($project->current_stage, ['development','completed'])) {
                return back()->withErrors(['stage' => 'Development tasks are locked until previous stages complete.']);
            }
        }

        $oldAssignee = $task->assigned_to;
        $task->update($validated);

        if (!empty($validated['assigned_to']) && $validated['assigned_to'] != $oldAssignee) {
            Notification::create([
                'user_id' => $validated['assigned_to'],
                'project_id' => $project->id,
                'task_id' => $task->id,
                'type' => Notification::TYPE_TASK_ASSIGNED,
                'message' => "You have been assigned to task '{$task->title}' in project '{$project->name}'.",
                'is_read' => false,
            ]);
        }

        $project->updateStage();

        return back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) abort(404);

        $task->delete();
        $project->updateStage();

        return back()->with('success', 'Task deleted.');
    }
}



