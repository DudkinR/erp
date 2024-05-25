<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'tn',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
     // add roles
    public function roles()
    {
        return $this->belongsToMany(Role::class , 'role_user');
    }
    public function hasRole($roleSlug)
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }
    // positions
    public function profile()
    {
        return $this->belongsTo(Personal::class, 'tn', 'tn');
    }

    public function positions()
    {
        return $this->hasManyThrough(Position::class, Personal::class, 'tn', 'id', 'tn', 'personal_id');
    }

    public function dependedPositions()
    {
        // Manually fetch depended positions from the profile
        return $this->profile ? $this->profile->dependedPositions() : collect();
    }

    public function tasks()
    {
        // Manually fetch tasks from the profile
        return $this->profile ? $this->profile->tasks() : collect();
    }

}
