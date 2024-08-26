<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

/// table name
    protected $table = 'rooms';
    protected $fillable = [
        'IDname',
        'name', 
        'description',
        'square',
        'floor',        
        'building_id',
        'category_PB',
        'RadiationSafetyZone',
        'owner_division',
        'owner_subdivision'
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
    // personal_room
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'personal_room', 'room_id', 'personal_id');
    }

}
