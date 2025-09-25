<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Task extends Model
{
    protected $fillable = [
        'project_id','title','description','assigned_to','stage','status','due_date'
    ];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
   public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }
    public static function allowedStatusesForStage($stage)
    {
        return match ($stage) {
            'product' => ['not_started','in_progress','complete'],
            'design' => ['not_started','in_progress','complete'],
            'development' => ['not_started','in_progress','testing','complete'],
            default => ['not_started','in_progress','testing','complete'],
        };
    }

    // helper to check overdue
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date instanceof Carbon && $this->due_date->isPast() && $this->status !== 'complete';
    }
}


// <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Task extends Model
// {
//      protected $fillable = [
//         'project_id','assigned_to','stage','title',
//         'description','status','due_date'
//     ];

//     public function project()
//     {
//         return $this->belongsTo(Project::class);
//     }

//     public function assignee()
//     {
//         return $this->belongsTo(User::class, 'assigned_to');
//     }

//     public function deliverables()
//     {
//         return $this->hasMany(Deliverable::class);
//     }
//      public static function allowedStatusesForStage(string $stage): array
//     {
//         return match ($stage) {
//             'product', 'design' => ['not_started', 'in_progress', 'complete'],
//             'development' => ['not_started', 'in_progress', 'testing', 'complete'],
//             default => ['not_started','in_progress','complete','testing'],
//         };
//     }

// }
