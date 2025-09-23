<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
     protected $fillable = [
        'project_id','assigned_to','stage','title',
        'description','status','due_date'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function deliverables()
    {
        return $this->hasMany(Deliverable::class);
    }

}
