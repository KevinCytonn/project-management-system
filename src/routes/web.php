<?php
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\{
    ProjectController,
    TaskController,
    DeliverableController,
    TeamController,
    UserController,
    ProfileController,
    DashboardController,
    NotificationController
};

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::resource('projects', ProjectController::class);

    
    Route::prefix('projects/{project}')->group(function () {
        Route::resource('tasks', TaskController::class);
    });

   
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('my.tasks');

  
    Route::post('/tasks/{task}/deliverables', [DeliverableController::class,'store'])->name('deliverables.store');
    Route::get('/deliverables/{deliverable}/view', [DeliverableController::class, 'view'])->name('deliverables.view');
    Route::get('/deliverables/{deliverable}/download', [DeliverableController::class, 'download'])->name('deliverables.download');
   
    Route::get('/files', [DeliverableController::class,'index'])->name('files');

   
     Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

  
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


