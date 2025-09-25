<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Notification;
use Carbon\Carbon;

class Project extends Model
{
    protected $fillable = [
        'name','description','requires_design','created_by','current_stage'
    ];

    protected $casts = [
        'requires_design' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected function setStageIfChanged(string $nextStage): void
    {
        if ($this->current_stage !== $nextStage) {
            $old = $this->current_stage;
            $this->current_stage = $nextStage;
            $this->save();

            // Notify project creator / owner
            if ($this->created_by) {
                Notification::create([
                    'user_id' => $this->created_by,
                    'project_id' => $this->id,
                    'task_id' => null,
                    'type' => Notification::TYPE_STAGE_ADVANCED,
                    'message' => "Project '{$this->name}' moved from {$old} to {$nextStage}",
                    'is_read' => false,
                ]);
            }

            // Stage completed notification
            if ($nextStage === 'completed' && $this->created_by) {
                Notification::create([
                    'user_id' => $this->created_by,
                    'project_id' => $this->id,
                    'task_id' => null,
                    'type' => Notification::TYPE_STAGE_COMPLETED,
                    'message' => "Project '{$this->name}' has been completed.",
                    'is_read' => false,
                ]);
            }
        }
    }

    public function updateStage(): void
    {
        // Fetch fresh tasks
        $productTasks = $this->tasks()->where('stage','product')->get();
        $designTasks = $this->tasks()->where('stage','design')->get();
        $devTasks = $this->tasks()->where('stage','development')->get();

        $allComplete = fn($collection, $allowed=['complete']) => $collection->count() === 0
            ? false
            : $collection->every(fn($t) => in_array($t->status, $allowed));

        // If product tasks exist and not all complete -> product
        if ($productTasks->count() > 0 && !$allComplete($productTasks, ['complete'])) {
            $this->setStageIfChanged('product');
            return;
        }

        // Product complete -> design or development
        if ($this->requires_design) {
            // If design tasks exist and not complete -> design
            if ($designTasks->count() > 0 && !$allComplete($designTasks, ['complete'])) {
                $this->setStageIfChanged('design');
                return;
            }

            // else design complete or no design tasks -> development
            $this->setStageIfChanged('development');
            // continue to check if development finished to mark completed
        } else {
            // skip design -> go to development
            $this->setStageIfChanged('development');
        }

        // If dev tasks exist and are all complete => completed
        if ($devTasks->count() > 0 && $devTasks->every(fn($t) => $t->status === 'complete')) {
            $this->setStageIfChanged('completed');
        }
    }
}

// <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Notifications\Notifiable;
// use App\Models\Notification;
// use App\Notifications\ProjectStageChanged; // implement as needed
// use App\Notifications\StageCompletedNotification; // implement as needed

// class Project extends Model
// {
//     use Notifiable;

//     protected $fillable = [
//         'name',
//         'description',
//         'requires_design',
//         'created_by',
//         'current_stage',
//     ];

//     protected $casts = [
//         'requires_design' => 'boolean',
//     ];

//     public function creator()
//     {
//         return $this->belongsTo(User::class, 'created_by');
//     }

//     public function tasks(): HasMany
//     {
//         return $this->hasMany(Task::class);
//     }

//     /**
//      * Recalculate project stage based on tasks and requirements.
//      * Called after task create/update/delete/status changes.
//      */
//     public function updateStage(): void
//     {
//         // Get tasks grouped by stage
//         $productTasks = $this->tasks()->where('stage', 'product')->get();
//         $designTasks = $this->tasks()->where('stage', 'design')->get();
//         $devTasks = $this->tasks()->where('stage', 'development')->get();

//         // Helper
//         $allComplete = fn($collection, $allowedCompleteStatuses = ['complete']) => $collection->count() === 0
//             ? false
//             : $collection->every(fn($t) => in_array($t->status, $allowedCompleteStatuses));

//         // If any product tasks exist and not all complete, project stays in product
//         if ($productTasks->count() > 0 && !$allComplete($productTasks)) {
//             $this->setStageIfChanged('product');
//             return;
//         }

//         // Product tasks are complete -> next stage could be design or development
//         if ($this->requires_design) {
//             // if design tasks exist and not complete => design
//             if ($designTasks->count() > 0 && !$allComplete($designTasks)) {
//                 $this->setStageIfChanged('design');
//                 return;
//             }

//             // design tasks complete (or none exist) => development
//             $this->setStageIfChanged('development');
//             return;
//         } else {
//             // skip design
//             $this->setStageIfChanged('development');
//             return;
//         }

//         // final: if development tasks all complete + testing passed/complete -> completed
//         if ($devTasks->count() > 0 && $devTasks->every(fn($t) => in_array($t->status, ['complete', 'testing']))) {
//             // require that all dev tasks are at least testing/complete and then final complete?
//             // To be conservative, mark completed only if all are 'complete'
//             if ($devTasks->every(fn($t) => $t->status === 'complete')) {
//                 $this->setStageIfChanged('completed');
//             }
//         }
//     }

//     /**
//      * Set current_stage and notify on change.
//      */
//    protected function setStageIfChanged(string $nextStage): void
// {
//     if ($this->current_stage !== $nextStage) {
//         $old = $this->current_stage;
//         $this->current_stage = $nextStage;
//         $this->save();

//         // Create custom notification for project creator
//         Notification::create([
//             'user_id'    => $this->created_by,
//             'project_id' => $this->id,
//             'type'       => Notification::TYPE_STAGE_ADVANCED,
//             'message'    => "Project '{$this->name}' advanced from {$old} to {$nextStage}",
//             'is_read'    => false,
//         ]);

//         // If completed, also log "stage_completed"
//         if ($nextStage === 'completed') {
//             Notification::create([
//                 'user_id'    => $this->created_by,
//                 'project_id' => $this->id,
//                 'type'       => Notification::TYPE_STAGE_COMPLETED,
//                 'message'    => "Project '{$this->name}' is completed ",
//                 'is_read'    => false,
//             ]);
//         }
//     }
// }
// }




//  <
// ?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Project extends Model
// {
//     protected $fillable = [
//         'name','description','created_by','requires_design','current_stage'
//     ];
//     public function creator()
//     {
//         return $this->belongsTo(User::class, 'created_by');
//     }
//        public function tasks()
//     {
//         return $this->hasMany(Task::class);
//     }
//      public function notifications()
//     {
//         return $this->hasMany(Notification::class);
//     }
//     public function updateStage()
// {
//     $productComplete = $this->tasks()->where('stage','product')->where('status','!=','complete')->count() === 0;
//     $designComplete = $this->tasks()->where('stage','design')->where('status','!=','complete')->count() === 0;
//     $developmentComplete = $this->tasks()->where('stage','development')->where('status','!=','complete')->count() === 0;

//     if ($productComplete && $this->current_stage == 'product') {
//         $this->current_stage = $this->requires_design ? 'design' : 'development';
//     } elseif ($designComplete && $this->current_stage == 'design') {
//         $this->current_stage = 'development';
//     } elseif ($developmentComplete && $this->current_stage == 'development') {
//         $this->current_stage = 'completed';
//     }

//     $this->save();
// }

// } -->
