<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    // table name
    protected $table = 'division';
    // fillable
    protected $fillable = ['name', 'description', 'abv', 'slug', 'parent_id'];
    // relationships
    public function children()
    {
        return $this->hasMany(Division::class, 'parent_id', 'id');
    }
    public function parent()
    {
        return $this->belongsTo(Division::class, 'parent_id', 'id');
    }
    // position_division
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_division');
    }
    // structure_division
    public function structures()
    {
        return $this->belongsToMany(Struct::class, 'structure_division');
    }
    // personal_division
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'personal_division');
    }

}
