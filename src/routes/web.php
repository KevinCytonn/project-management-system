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

    // Projects resource (project-level routes)
    Route::resource('projects', ProjectController::class);

    // Nested tasks under project (keeps existing nesting)
    Route::prefix('projects/{project}')->group(function () {
        Route::resource('tasks', TaskController::class);
    });

    // Global "my tasks" (sidebar link)
    Route::get('/my-tasks', [TaskController::class, 'myTasks'])->name('my.tasks');

    // Deliverables
    Route::post('/tasks/{task}/deliverables', [DeliverableController::class,'store'])->name('deliverables.store');
    Route::get('/deliverables/{deliverable}/download', [DeliverableController::class,'download'])->name('deliverables.download');
    Route::get('/files', [DeliverableController::class,'index'])->name('files');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');

    // Team/users/profile
    Route::get('/team', [TeamController::class, 'index'])->name('team');
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



// Route::get('/', function () {
//     $user = request()->user();

//     if ($user) {
//         return redirect()->route('dashboard');
//     }

//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// // routes/web.php
// Route::middleware('auth')->group(function () {
//   Route::resource('projects', ProjectController::class);
//     Route::prefix('projects/{project}')->group(function () {
//       Route::resource('tasks', TaskController::class);
//     });
//     Route::post('/tasks/{task}/deliverables', [FileController::class,'store'])->name('deliverables.store');
//     Route::get('/deliverables/{deliverable}/download', [FileController::class,'download'])->name('deliverables.download');

//     Route::get('/team', [TeamController::class, 'index'])->name('team');
//     Route::get('/files', [FileController::class, 'index'])->name('files');

//     Route::get('/users', [UserController::class, 'index'])->name('users');
//     Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


// Route::get('/', function () {
// //     return Inertia::render('Welcome', [
// //         'canLogin' => Route::has('login'),
// //         'canRegister' => Route::has('register'),
// //         'laravelVersion' => Application::VERSION,
// //         'phpVersion' => PHP_VERSION,
// //     ]);
// // });
// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     Route::resource('projects', ProjectController::class);

//     Route::prefix('projects/{project}')->group(function () {
//         Route::resource('tasks', TaskController::class);
//     });

//     Route::post('/tasks/{task}/deliverables', [DeliverableController::class,'store'])->name('deliverables.store');
//     Route::get('/deliverables/{deliverable}/download', [DeliverableController::class,'download'])->name('deliverables.download');

//     Route::get('/team', [TeamController::class, 'index'])->name('team');
//     Route::get('/files', [FileController::class, 'index'])->name('files');

//     Route::get('/users', [UserController::class, 'index'])->name('users');
//     Route::post('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');

//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });


