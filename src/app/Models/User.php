<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_approved'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function role(){
        return $this->belongsTo(Role::class);
    }
      public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
     public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }
      public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public static function visibleUsers($currentUser)
    {
         if ($currentUser->role->name === 'admin') {
            $users = User::with('role')->get();
        } else {
         
            $users = User::with('role')->whereHas('role', function ($query) use ($currentUser) {
                if ($currentUser->role->name === 'development_manager') {
                    $query->where('name', 'developer');
                } elseif ($currentUser->role->name === 'designer_manager') {
                    $query->where('name', 'designer');
                } elseif ($currentUser->role->name === 'product_manager') {
                    $query->where('name', 'analyst');
                }
            })->get();
        }
        return $users;
    }
}
