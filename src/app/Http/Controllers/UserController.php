<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = $request->user();

        // Admin sees everyone
        if ($currentUser->role->name === 'admin') {
            $users = User::with('role')->get();
        } else {
            // Managers only see their team
            $users = User::with('role')->whereHas('role', function ($query) use ($currentUser) {
                if ($currentUser->role->name === 'software_manager') {
                    $query->where('name', 'developer');
                } elseif ($currentUser->role->name === 'designer_manager') {
                    $query->where('name', 'designer');
                } elseif ($currentUser->role->name === 'product_manager') {
                    $query->where('name', 'analyst');
                }
            })->get();
        }

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function verify(Request $request, User $user)
    {
        $currentUser = $request->user();

        $canVerify = match ($currentUser->role->name) {
            'admin' => true,
            'software_manager' => $user->role->name === 'developer',
            'designer_manager' => $user->role->name === 'designer',
            'product_manager' => $user->role->name === 'analyst',
            default => false,
        };

        if (!$canVerify) {
            abort(403, 'You are not allowed to verify this user.');
        }

        $user->update(['is_approved' => true]);

        return redirect()->route('users')->with('success', 'User verified successfully.');
    }
}
