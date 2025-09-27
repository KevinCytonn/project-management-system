<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with('creator')
            ->latest()
            ->paginate(10)
            ->through(fn($project) => [
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
    public function show(Project $project)
{
   
    $tasks = $project->tasks()->with('assignee')->get();

    return Inertia::render('Projects/ProjectDetails', [
        'project' => $project,
        'tasks' => $tasks,
    ]);
}

    public function create(Request $request)
    {
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

        Project::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'requires_design' => $validated['requires_design'] ?? false,
            'created_by' => $request->user()->id,
            'current_stage' => 'product',
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Request $request, Project $project)
    {
        $user = $request->user();

        if ($user->role->name !== 'product_manager' || $user->id !== $project->created_by) {
            abort(403);
        }

        $tasks = $project->tasks()->with('assignee')->get();

        return Inertia::render('Projects/ProjectDetails', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    public function update(Request $request, Project $project)
    {
        $user = $request->user();

        if ($user->role->name !== 'product_manager' || $user->id !== $project->created_by) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'requires_design' => 'boolean',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Request $request, Project $project)
    {
        $user = $request->user();

        if ($user->role->name !== 'product_manager' || $user->id !== $project->created_by) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}

