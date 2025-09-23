<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const TYPE_STAGE_ADVANCED = 'stage_advanced';
    const TYPE_TASK_ASSIGNED = 'task_assigned';
    const TYPE_TASK_OVERDUE = 'task_overdue';
    const TYPE_STAGE_COMPLETED = 'stage_completed';

    protected $fillable = [
        'user_id','project_id','task_id','type','message','is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
