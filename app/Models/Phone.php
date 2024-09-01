<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    use HasFactory;
    //table name
    protected $table = 'phone';
    //columns
    protected $fillable = [      
        'phone',      
    ]; 
    //rooms phone_room
    public function rooms()
    {
        return $this-> belongsToMany(Room::class , 'phone_room', 'phone_id', 'room_id');
    }
    //personal personal_phone
    public function personals()
    {
        return $this-> belongsToMany(Personal::class , 'personal_phone', 'phone_id', 'personal_id');
    }

}
