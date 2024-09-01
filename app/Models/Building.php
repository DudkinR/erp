<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class Building extends Model
{
    use HasFactory;
    //table name
    protected $table = 'building';
    //columns
    protected $fillable = [
        'IDBuilding',
        'name',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'abv',
        'slug',
        'organization',
        'status',
        'image'
    ];
    //building_room belongs to building

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'building_room', 'building_id', 'room_id');
    }
     

     
}
