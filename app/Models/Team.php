<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //
    protected $table = 'teams';
    protected $fillable = ['name', 'description'];
    public function users()
    {
        return $this->belongsToMany(User::class, 'teams_users', 'team_id', 'user_id')->withTimestamps();
    }
    public function usersWithRole()
    {
        return $this->belongsToMany(User::class, 'teams_users_role', 'team_id', 'user_id')
            ->withPivot('role')
            ->withTimestamps();
    }
     public function tasks()
    {
        return $this->hasMany(TeamTask::class, 'team_id');
    }
}
