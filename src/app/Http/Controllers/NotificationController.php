<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Notification as AppNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $notifications = AppNotification::where('user_id', $user->id)
            ->latest()
            ->get();

        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(AppNotification $notification)
    {
        if ($notification->user_id !== auth()->id()) abort(403);

        $notification->update(['is_read' => true]);

        return back()->with('success', 'Notification marked as read');
    }
}

// <?php

// namespace App\Http\Controllers;

// use App\Models\Notification;
// use Illuminate\Http\Request;
// use Inertia\Inertia;

// class NotificationController extends Controller
// {
//     public function index(Request $request)
//     {
//         $notifications = Notification::where('user_id', $request->user()->id)
//             ->latest()
//             ->get();

//         return Inertia::render('Notifications/Index', [
//             'notifications' => $notifications,
//         ]);
//     }

//     public function markAsRead(Notification $notification)
//     {
//         if ($notification->user_id !== auth()->id()) {
//             abort(403);
//         }

//         $notification->update(['is_read' => true]);

//         return back()->with('success', 'Notification marked as read.');
//     }
// }
