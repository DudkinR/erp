<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    // table name
    protected $table = 'positions';
    // fillable fields 
        protected $fillable = ['name', 'description', 'start', 'data_start', 'closed', 'data_closed'];

    // relationships
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'personal_position', 'position_id', 'personal_id');
    }

    public function structuries()
    {
        return $this->belongsToMany(Struct::class, 'positions_structuries', 'positions_id', 'structuries_id');
    }
    // positions_functs
    public function funs()
    {
        return $this->belongsToMany(Fun::class, 'positions_functs', 'position_id', 'funct_id');
    }
    // positions_divisions
    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'position_division', 'position_id', 'division_id');
    }
    // position_room
    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'position_room', 'position_id', 'room_id');
    }
    // position_building

    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'position_building', 'position_id', 'building_id');
    }

}
