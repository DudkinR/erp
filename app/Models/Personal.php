<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    // table name
    protected $table = 'personal';

    // fillable fields `tn`, `nickname`, `fio`, `email`, `phone`, `date_start`, `status`, `date_status`

    protected $fillable = ['tn', 'nickname', 'fio', 'email', 'phone', 'date_start' , 'status', 'date_status'];

  
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'personal_position', 'personal_id', 'position_id');
    }

    public function dependedPositions()
    {
        // First, we get all positions for this personal
        $positions = $this->positions;

        // Get all structuries for these positions
        $structuries = $positions->flatMap(function ($position) {
            return $position->structuries;
        });

        // Get all child structuries
        $childStructuries = $structuries->flatMap(function ($structury) {
            return $structury->childStructuries;
        });

        // Get all positions for these child structuries
        return $childStructuries->flatMap(function ($childStructury) {
            return $childStructury->positions;
        })->unique('id');
    }
    // связуем roles через  user profile.tn=user.tn у юзера есть роли которые соответствуют персоналу
    public function user()
    {
        return $this->hasOne(User::class, 'tn', 'tn');
    }
    public function roles()
    {
       return $this->user->roles();
    }

    public function tasks()
    {
        return Task::whereIn('responsible_position_id', $this->positions->pluck('id'))->get();
    }
    // personal_comment - таблица связи belongtomany

    public function comments()
    {
        return $this->belongsToMany(Comment::class, 'personal_comment', 'personal_id', 'comment_id');
    }
}
