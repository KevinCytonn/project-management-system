<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $role = $user->role->name;

        $data = [];

        switch ($role) {
            case 'admin':
                $data['projects'] = Project::with('creator')->latest()->take(6)->get();
                $data['tasks'] = Task::with(['project', 'assignee'])->latest()->take(10)->get();
                $data['users'] = User::with('role')->get();
                return Inertia::render('Dashboards/AdminDashboard', $data);

            case 'product_manager':
                // Only product stage projects
                $projects = Project::with('creator')
                    ->where('current_stage', 'product')
                    ->latest()
                    ->take(6)
                    ->get();

                // Tasks in product stage
                $tasks = Task::with(['project', 'assignee'])
                    ->where('stage', 'product')
                    ->latest()
                    ->take(10)
                    ->get();

                $data = [
                    'projects' => $projects,
                    'tasks' => $tasks,
                ];

                return Inertia::render('Dashboards/ProductManagerDashboard', $data);

            case 'design_manager':
                $projects = Project::with('creator')
                    ->where('current_stage', 'design')
                    ->latest()
                    ->take(6)
                    ->get();

                $tasks = Task::with(['project', 'assignee'])
                    ->where('stage', 'design')
                    ->latest()
                    ->take(10)
                    ->get();

                $data = [
                    'projects' => $projects,
                    'tasks' => $tasks,
                ];

                return Inertia::render('Dashboards/DesignerManagerDashboard', $data);

            case 'development_manager':
                $projects = Project::with('creator')
                    ->where('current_stage', 'development')
                    ->latest()
                    ->take(6)
                    ->get();

                $tasks = Task::with(['project', 'assignee'])
                    ->where('stage', 'development')
                    ->latest()
                    ->take(10)
                    ->get();

                $data = [
                    'projects' => $projects,
                    'tasks' => $tasks,
                ];

                return Inertia::render('Dashboards/SoftwareManagerDashboard', $data);

            case 'developer':
            case 'designer':
            case 'analyst':
            case 'member':
            default:
                $tasks = Task::with(['project', 'assignee'])
                    ->where('assigned_to', $user->id)
                    ->latest()
                    ->take(10)
                    ->get();

                $summary = [
                    'total' => $tasks->count(),
                    'not_started' => $tasks->where('status', 'not_started')->count(),
                    'in_progress' => $tasks->where('status', 'in_progress')->count(),
                    'testing' => $tasks->where('status', 'testing')->count(),
                    'complete' => $tasks->where('status', 'complete')->count(),
                    'overdue' => $tasks->filter(fn($t) => $t->due_date && $t->due_date->isPast() && $t->status !== 'complete')->count(),
                ];

                return Inertia::render('Dashboards/MemberDashboard', [
                    'tasks' => $tasks,
                    'summary' => $summary,
                ]);
        }
    }
}
