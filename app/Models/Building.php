<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    //table name
    protected $table = 'building';
    //columns
    protected $fillable = [
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
    //rooms building_id

    public function rooms()
    {
        return $this->hasMany(Room::class);  
    }
     

     
}
