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
 

}
