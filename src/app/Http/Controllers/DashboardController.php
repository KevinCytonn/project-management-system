<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role->name) {
            'admin' => Inertia::render('Dashboards/AdminDashboard'),
            'product_manager' => Inertia::render('Dashboards/ProductManagerDashboard'),
            'software_manager' => Inertia::render('Dashboards/SoftwareManagerDashboard'),
            'designer_manager' => Inertia::render('Dashboards/DesignerManagerDashboard'),
            'developer', 'designer', 'analyst', 'member' => Inertia::render('Dashboards/MemberDashboard'),
            default => Inertia::render('Dashboards/MemberDashboard'),
        };
    }
}
