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
    // personal_room belongs to building
    public function personals()
    {
        return Personal::whereHas('rooms', function ($query) {
            $query->whereHas('buildings', function ($subQuery) {
                $subQuery->where('buildings.id', $this->id);
            });
        })->get();
    }
    //division belongs to building
    public function divisions()
    {
        return Division::whereHas('personals', function ($query) {
            $query->whereHas('rooms', function ($subQuery) {
                $subQuery->whereHas('buildings', function ($nestedQuery) {
                    $nestedQuery->where('building.id', $this->id); // Correct the table name here
                });
            });
        })->get();
    }

     

     
}
