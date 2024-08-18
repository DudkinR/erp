<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'IDname',
        'name', 
        'description',
        'square',
        'floor',
    ];
    // phone_room
    public function phones()
    {
        return $this->belongsToMany(Phone::class, 'phone_room', 'room_id', 'phone_id');
    }
    // building_room
    public function buildings()
    {
        return $this->belongsToMany(Building::class, 'building_room', 'room_id', 'building_id');
    }
}
