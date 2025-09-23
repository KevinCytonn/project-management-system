<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name','description','created_by','requires_design','current_stage'
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
       public function tasks()
    {
        return $this->hasMany(Task::class);
    }
     public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
