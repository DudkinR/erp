<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    // table name
    protected $table = 'personal';

    // fillable fields
    protected $fillable = ['tn', 'nickname', 'fio', 'email', 'phone', 'date_start'];

  
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

    public function tasks()
    {
        return Task::whereIn('responsible_position_id', $this->positions->pluck('id'))->get();
    }
}
