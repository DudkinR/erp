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
    // personal_divition - таблица связи belongtomany
    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'personal_division', 'personal_id', 'division_id');
    }
    // personal_briefing - таблица связи  one 
    public function briefings()
    {
        return $this->belongsToMany(Briefing::class, 'briefing_personal', 'personal_id', 'briefing_id');
    }
    // personal_phone - таблица связи  one
    public function phones()
    {
        return $this->belongsToMany(Phone::class, 'personal_phone', 'personal_id', 'phone_id');
    }
    // personal_position - таблица связи  one

    // personal_room - таблица связи  one
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'personal_room', 'personal_id', 'room_id');
    }
    // personal_building - таблица связи  one
    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'personal_building', 'personal_id', 'building_id');
    }


}
