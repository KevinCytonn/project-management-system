<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
   

    // List projects - Admin sees all; managers see relevant projects; product managers see their projects (created_by)
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Project::with('creator');

        if ($user->role->name === 'product_manager') {
            $query->where('created_by', $user->id);
        } elseif ($user->role->name !== 'admin') {
            // Non-admin non-product managers see projects where they have tasks assigned
            $query->whereHas('tasks', fn($q) => $q->where('assigned_to', $user->id));
        }

        $projects = $query->latest()->paginate(10)->through(fn($project) => [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
            'requires_design' => (bool) $project->requires_design,
            'current_stage' => $project->current_stage,
            'creator' => [
                'id' => $project->creator->id ?? null,
                'name' => $project->creator->name ?? '—',
            ],
            'created_at' => $project->created_at->diffForHumans(),
        ]);

        return Inertia::render('Projects/ProjectList', ['projects' => $projects]);
    }

    public function create(Request $request)
    {
        // only product managers can create projects
        if ($request->user()->role->name !== 'product_manager') {
            abort(403);
        }
        return Inertia::render('Projects/CreateProject');
    }

    public function store(Request $request)
    {
        if ($request->user()->role->name !== 'product_manager') {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_design' => 'boolean',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'requires_design' => $validated['requires_design'] ?? false,
            'created_by' => Auth::id(),
            'current_stage' => 'product',
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Request $request, Project $project)
    {
        // Admin can view; product managers who created it can view; team members assigned can view
        $user = $request->user();
        if ($user->role->name !== 'admin' && $user->id !== $project->created_by && !$project->tasks()->where('assigned_to', $user->id)->exists()) {
            abort(403);
        }

        // send tasks for that project
        $tasks = $project->tasks()->with('assignee')->get();

        return Inertia::render('Projects/ProjectDetails', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        // Only admins or product managers who created project can update metadata (NOT stage).
        $user = $request->user();
        if (!in_array($user->role->name, ['admin','product_manager']) || ($user->role->name === 'product_manager' && $user->id !== $project->created_by)) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_design' => 'boolean',
            // do not accept direct current_stage change from UI
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Request $request, Project $project)
    {
        $user = $request->user();
        if ($user->role->name !== 'admin' && !($user->role->name === 'product_manager' && $user->id === $project->created_by)) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}

// <?php

// namespace App\Http\Controllers;

// use App\Models\Project;
// use Illuminate\Http\Request;
// use Inertia\Inertia;
// use Illuminate\Support\Facades\Auth;

// class ProjectController extends Controller
// {
//     public function index()
//     {
//         $projects = Project::with('creator')
//             ->latest()
//             ->paginate(10)
//             ->through(fn ($project) => [
//                 'id' => $project->id,
//                 'name' => $project->name,
//                 'description' => $project->description,
//                 'requires_design' => $project->requires_design,
//                 'current_stage' => $project->current_stage,
//                 'creator' => [
//                     'id' => $project->creator->id,
//                     'name' => $project->creator->name,
//                 ],
//                 'created_at' => $project->created_at->diffForHumans(),
//             ]);

//         return Inertia::render('Projects/ProjectList', ['projects' => $projects]);
//     }

//     public function create()
//     {
//         return Inertia::render('Projects/CreateProject');
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'description' => 'nullable|string',
//             'requires_design' => 'boolean',
//         ]);

//         Project::create([
//             ...$validated,
//             'created_by' => Auth::id(),
//             'current_stage' => 'product',
//         ]);

//         return redirect()->route('projects.index')->with('success', 'Project created successfully.');
//     }

//     public function edit(Project $project)
//     {
//         return Inertia::render('Projects/ProjectDetails', ['project' => $project]);
//     }

//     public function update(Request $request, Project $project)
//     {
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'description' => 'nullable|string',
//             'requires_design' => 'boolean',
//             'current_stage' => 'in:product,design,development,completed',
//         ]);

//         $project->update($validated);

//         return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
//     }

//     public function destroy(Project $project)
//     {
//         $project->delete();

//         return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
//     }
// }




//  <
//  ?php

// // namespace App\Http\Controllers;

// // use App\Models\Project;
// // use Illuminate\Http\Request;
// // use Inertia\Inertia;
// // use Illuminate\Support\Facades\Auth;

// class ProjectController extends Controller
// {
//     public function index()
//     {
//         $projects = Project::with('creator')
//             ->latest()
//             ->paginate(10)
//             ->through(fn ($project) => [
//                 'id' => $project->id,
//                 'name' => $project->name,
//                 'description' => $project->description,
//                 'requires_design' => $project->requires_design,
//                 'current_stage' => $project->current_stage,
//                 'creator' => [
//                     'id' => $project->creator->id,
//                     'name' => $project->creator->name,
//                 ],
//                 'created_at' => $project->created_at->diffForHumans(),
//             ]);

//         return Inertia::render('Projects/Index', ['projects' => $projects]);
//     }

//     public function create()
//     {
//         return Inertia::render('Projects/Create');
//     }

//     public function store(Request $request)
//     {
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'description' => 'nullable|string',
//             'requires_design' => 'boolean',
//         ]);

//         Project::create([
//             ...$validated,
//             'created_by' => Auth::id(),
//             'current_stage' => 'product',
//         ]);

//         return redirect()->route('projects.index')->with('success', 'Project created successfully.');
//     }

//     public function edit(Project $project)
//     {
//         return Inertia::render('Projects/Edit', ['project' => $project]);
//     }

//     public function update(Request $request, Project $project)
//     {
//         $validated = $request->validate([
//             'name' => 'required|string|max:255',
//             'description' => 'nullable|string',
//             'requires_design' => 'boolean',
//             'current_stage' => 'in:product,design,development,completed',
//         ]);

//         $project->update($validated);

//         return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
//     }

//     public function destroy(Project $project)
//     {
//         $project->delete();

//         return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
//     }
// } 
//  -->


 