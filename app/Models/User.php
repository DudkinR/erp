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
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'tn', 'tn');
    }
    public function positions()
    {
        // return $user->profile->positions;
        return $this->profile->positions;
      
    }
    // division
    public function division()
    {
        return $this->profile ?$this->profile->division : null;
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
    public function relatedUsers()
    {
        return $this->belongsToMany(
            User::class,          // зв'язуємо саму модель User
            'user_user',         // таблиця
            'user_id',          // поле в user_user яке вказує на цього користувача
            'related_user_id'   // поле на пов'язаний користувач
        );
    }
    // back relations user_user
    public function relatedBack()
    {
        return $this->belongsToMany(
            User::class,          // зв'язуємо саму модель User
            'user_user',         // таблиця
            'related_user_id',   // поле в user_user яке вказує на цього користувача
            'user_id'          // поле на пов'язаний користувач
        );
    }

}
