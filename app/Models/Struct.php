<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struct extends Model
{
    use HasFactory;
    // table name
    protected $table = 'structuries';
    // fillable
    protected $fillable = ['abv','name', 'description','parent_id','kod','status'];
    // relationships positions
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'positions_structuries', 'structuries_id', 'positions_id');
    }
    // parent 
    public function parent()
    {
        return $this->belongsTo(Struct::class, 'parent_id', 'id');
    }
    // children
    public function children()
    {
        return $this->hasMany(Struct::class, 'parent_id', 'id');
    }
    // structure_division
    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'structure_division', 'struct_id', 'division_id');
    }
}
