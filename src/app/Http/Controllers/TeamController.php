<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = $request->user();
        $users =User::visibleUsers($currentUser);
 
        $teamMembers = collect($users);

        
        return Inertia::render('Members/Index', [
            'teamMembers' => $teamMembers,
        ]);
    }
}
