<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Notification;
use App\Models\User;

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

    /**
     * Notify managers and/or users when project stage changes
     */
    protected function notifyStageChange(string $old, string $nextStage): void
    {
        // Map stage → manager role
        $stageToManager = [
            'product'      => 'product_manager',
            'design'       => 'design_manager',
            'development'  => 'development_manager',
        ];

        // 1. Notify the manager of the new stage
        if (isset($stageToManager[$nextStage])) {
            $manager = User::whereHas('role', fn($q) => $q->where('name', $stageToManager[$nextStage]))->first();

            if ($manager) {
                Notification::create([
                    'user_id'    => $manager->id,
                    'project_id' => $this->id,
                    'task_id'    => null,
                    'type'       => Notification::TYPE_STAGE_ADVANCED,
                    'message'    => "Project '{$this->name}' has entered the {$nextStage} stage.",
                    'is_read'    => false,
                ]);
            }
        }

        
        if (isset($stageToManager[$old])) {
            $manager = User::whereHas('role', fn($q) => $q->where('name', $stageToManager[$old]))->first();

            if ($manager) {
                Notification::create([
                    'user_id'    => $manager->id,
                    'project_id' => $this->id,
                    'task_id'    => null,
                    'type'       => Notification::TYPE_STAGE_ADVANCED,
                    'message'    => "Project '{$this->name}' has moved from {$old} to {$nextStage}.",
                    'is_read'    => false,
                ]);
            }
        }

        // 3. Notify all users when project is completed
        if ($nextStage === 'completed') {
            $allUsers = User::all();
            foreach ($allUsers as $user) {
                Notification::create([
                    'user_id'    => $user->id,
                    'project_id' => $this->id,
                    'task_id'    => null,
                    'type'       => Notification::TYPE_STAGE_COMPLETED,
                    'message'    => "Project '{$this->name}' has been completed successfully!",
                    'is_read'    => false,
                ]);
            }
        }
    }

    /**
     * Change stage if required and notify
     */
    protected function setStageIfChanged(string $nextStage): void
    {
        if ($this->current_stage !== $nextStage) {
            $old = $this->current_stage;
            $this->current_stage = $nextStage;
            $this->save();

            $this->notifyStageChange($old, $nextStage);
        }
    }

    /**
     * Automatically update stage based on tasks
     */
    public function updateStage(): void
    {
        $productTasks = $this->tasks()->where('stage','product')->get();
        $designTasks  = $this->tasks()->where('stage','design')->get();
        $devTasks     = $this->tasks()->where('stage','development')->get();

        $allComplete = fn($collection, $allowed=['complete']) =>
            $collection->count() > 0 && $collection->every(fn($t) => in_array($t->status, $allowed));

       
        if ($productTasks->count() > 0 && !$allComplete($productTasks)) {
            $this->setStageIfChanged('product');
            return;
        }

       
        if ($this->requires_design) {
            if ($designTasks->count() > 0 && !$allComplete($designTasks)) {
                $this->setStageIfChanged('design');
                return;
            }

            if ($designTasks->count() > 0 && $allComplete($designTasks)) {
                $this->setStageIfChanged('development');
                return;
            }

            if ($designTasks->count() === 0) {
                $this->setStageIfChanged('design');
                return;
            }
        } else {
            $this->setStageIfChanged('development');
        }

       
        if ($devTasks->count() > 0 && $allComplete($devTasks)) {
            $this->setStageIfChanged('completed');
        }
    }
}
