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
      
        $productTasks = $this->tasks()->where('stage','product')->get();
        $designTasks = $this->tasks()->where('stage','design')->get();
        $devTasks = $this->tasks()->where('stage','development')->get();

        $allComplete = fn($collection, $allowed=['complete']) => $collection->count() === 0
            ? false
            : $collection->every(fn($t) => in_array($t->status, $allowed));

       
        if ($productTasks->count() > 0 && !$allComplete($productTasks, ['complete'])) {
            $this->setStageIfChanged('product');
            return;
        }

       
        if ($this->requires_design) {
           
            if ($designTasks->count() > 0 && !$allComplete($designTasks, ['complete'])) {
                $this->setStageIfChanged('design');
                return;
            }

           
            $this->setStageIfChanged('development');
            
        } else {
          
            $this->setStageIfChanged('development');
        }

       
        if ($devTasks->count() > 0 && $devTasks->every(fn($t) => $t->status === 'complete')) {
            $this->setStageIfChanged('completed');
        }
    }
}
