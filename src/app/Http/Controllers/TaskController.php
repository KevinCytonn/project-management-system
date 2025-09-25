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
    // Index: tasks for a given project
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

    // Global "My Tasks" - used by sidebar link for each user
    public function myTasks(Request $request)
    {
        $user = $request->user();

        $tasks = Task::with('project')
            ->where('assigned_to', $user->id)
            ->get();

        // Summary counts for dashboard
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

    // Create task form (shows members)
    public function create(Project $project,Request $request)
    {
        $currentUser = $request->user();
        $users =User::visibleUsers($currentUser);

        return Inertia::render('Tasks/TaskUpload', [
            'project' => $project,
            'members' => $users,
        ]);
    }

    // Store a new task
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'stage' => ['required', Rule::in(['product','design','development'])],
            'status' => ['required', Rule::in(Task::allowedStatusesForStage(request('stage')))],
            'due_date' => 'nullable|date',
        ]);

        // Enforce stage-locking: can't create development tasks if project not at development/completed
        if ($validated['stage'] === 'development' && !in_array($project->current_stage, ['development','completed'])) {
            return back()->withErrors(['stage' => 'Development is locked until previous stages complete.']);
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

        // Add custom Notification for assignee
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

        // Auto-advance stage if needed
        $project->updateStage();

        return redirect()->route('tasks.index', $project->id)->with('success', 'Task created successfully.');
    }

    // Edit view (manager)
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

    // Update: supports partial status updates (assignee) and full updates (manager)
    public function update(Request $request, Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) abort(404);

        // If request is only updating status (assignee action)
        if ($request->has('status') && $request->except('status') === []) {
            $validated = $request->validate([
                'status' => ['required', Rule::in(Task::allowedStatusesForStage($task->stage))],
            ]);

            $task->update(['status' => $validated['status']]);

            // If status move to complete may trigger project stage update
            $project->updateStage();

            return back()->with('success', 'Task status updated.');
        }

        // Manager/Full update
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => ['required', Rule::in(Task::allowedStatusesForStage($task->stage))],
            'due_date' => 'nullable|date',
            'stage' => ['sometimes', Rule::in(['product','design','development'])],
        ]);

        // If stage change to development while locked -> reject
        if (isset($validated['stage']) && $validated['stage'] === 'development'
            && !in_array($project->current_stage, ['development','completed'])) {
            return back()->withErrors(['stage' => 'Development is locked until previous stages complete.']);
        }

        $oldAssignee = $task->assigned_to;
        $task->update($validated);

        // If reassigned notify new assignee
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

    // Delete task
    public function destroy(Project $project, Task $task)
    {
        if ($task->project_id !== $project->id) abort(404);

        $task->delete();
        $project->updateStage();

        return back()->with('success', 'Task deleted.');
    }
}

// <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Inertia\Inertia;
// use App\Models\Task;
// use App\Models\Project;
// use App\Models\User;
// use Illuminate\Validation\Rule;
// // use Illuminate\Support\Facades\Notification;
// use App\Notifications\TaskAssignedNotification; // implement this
// use App\Models\Notification;

// class TaskController extends Controller
// {
//     // public function __construct()
//     // {
//     //     $this->middleware('auth');
//     // }

//     public function index(Project $project)
//     {
//         $tasks = $project->tasks()->with('assignee', 'deliverables')->get();

//         return Inertia::render('Tasks/TaskList', [
//             'project' => $project,
//             'tasks' => $tasks
//         ]);
//     }

//     public function create(Project $project)
//     {
//         // Only managers for relevant stage can create tasks:
//         // Product tasks -> product_manager; design tasks -> designer_manager; development -> software_manager
//         $members = User::all()->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'role' => $u->role]);
//         return Inertia::render('Tasks/TaskUpload', [
//             'project' => $project,
//             'members' => $members
//         ]);
//     }

   

// public function store(Request $request, Project $project)
// {
//     $validated = $request->validate([
//         'title' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'assigned_to' => 'nullable|exists:users,id',
//         'stage' => ['required', Rule::in(['product','design','development'])],
//         'status' => ['required'],
//         'due_date' => 'nullable|date',
//     ]);

//     $task = Task::create([
//         ...$validated,
//         'project_id' => $project->id,
//     ]);

//     // Custom notification for assignee
//     if ($task->assigned_to) {
//         Notification::create([
//             'user_id'    => $task->assigned_to,
//             'project_id' => $project->id,
//             'task_id'    => $task->id,
//             'type'       => Notification::TYPE_TASK_ASSIGNED,
//             'message'    => "You have been assigned task '{$task->title}' in project '{$project->name}'",
//             'is_read'    => false,
//         ]);
//     }

//     $project->updateStage();

//     return redirect()->route('tasks.index', $project->id)->with('success', 'Task created successfully.');
// }

// public function update(Request $request, Project $project, Task $task)
// {
//     $validated = $request->validate([
//         'title' => 'required|string|max:255',
//         'description' => 'nullable|string',
//         'assigned_to' => 'nullable|exists:users,id',
//         'status' => ['required'],
//         'due_date' => 'nullable|date',
//     ]);

//     $task->update($validated);

//     // If reassigned → notify
//     if (!empty($validated['assigned_to'])) {
//         Notification::create([
//             'user_id'    => $validated['assigned_to'],
//             'project_id' => $project->id,
//             'task_id'    => $task->id,
//             'type'       => Notification::TYPE_TASK_ASSIGNED,
//             'message'    => "You have been reassigned task '{$task->title}' in project '{$project->name}'",
//             'is_read'    => false,
//         ]);
//     }

//     $project->updateStage();

//     return back()->with('success', 'Task updated successfully.');
// }

//     public function destroy(Task $task)
//     {
//         $project = $task->project;
//         $task->delete();
//         $project->updateStage();

//         return back()->with('success', 'Task deleted successfully.');
//     }
// }





// <!-- <
// ?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Inertia\Inertia;
// use App\Models\Task;
// use App\Models\Project;
// use App\Models\User;

// class TaskController extends Controller
// {
//     public function index(Project $project)
//     {
//         $tasks = $project->tasks()->with('assignee', 'deliverables')->get();
//         return Inertia::render('Tasks/Index', [
//             'project' => $project,
//             'tasks' => $tasks
//         ]);
//     }

//     public function create(Project $project)
//     {
//         $members = User::all();
//         return Inertia::render('Tasks/Create', [
//             'project' => $project,
//             'members' => $members
//         ]);
//     }

//     public function store(Request $request, Project $project)
//     {
//         $validated = $request->validate([
//             'title' => 'required|string|max:255',
//             'description' => 'nullable|string',
//             'assigned_to' => 'required|exists:users,id',
//             'stage' => 'required|in:product,design,development',
//             'status' => 'required|in:not_started,in_progress,complete,testing',
//             'due_date' => 'nullable|date',
//         ]);

//         $task = Task::create([
//             ...$validated,
//             'project_id' => $project->id
//         ]);

//         $project->updateStage();

//         return redirect()->route('tasks.index', $project->id)
//                          ->with('success', 'Task created successfully.');
//     }

//     public function edit(Project $project ,Task $task)
//     {
//         $members = User::all();
//         return Inertia::render('Tasks/Edit', [
//             'task' => $task,
//             'members' => $members
//         ]);
//     }

//     public function update(Request $request, Project $project,Task $task)
//     {
//         $validated = $request->validate([
//             'title' => 'required|string|max:255',
//             'description' => 'nullable|string',
//             'assigned_to' => 'required|exists:users,id',
//             'status' => 'required|in:not_started,in_progress,complete,testing',
//             'due_date' => 'nullable|date',
//         ]);

//         $task->update($validated);
//         $task->project->updateStage();

//         return redirect()->back()->with('success', 'Task updated successfully.');
//     }

//     public function destroy(Task $task)
//     {
//         $task->delete();
//         $task->project->updateStage();

//         return redirect()->back()->with('success', 'Task deleted successfully.');
//     }
// } -->

