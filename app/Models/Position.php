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
    protected $fillable = ['name', 'description'];

    // relationships
    public function personals()
    {
        // personal_position
        return $this->belongsToMany( Personal::class, 'personal_position', 'position_id', 'personal_id' );
    }

}
