<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasKeywords;

class Division extends Model
{
    use HasFactory;
    use HasKeywords;
    // table name
    protected $table = 'division';
    // fillable
    protected $fillable = ['in_id','name', 'description', 'abv', 'slug', 'parent_id'];
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
    // Всередині класу Division

    public function personals()
{
    return $this->belongsToMany(Personal::class, 'personal_division', 'division_id', 'personal_id')
                ->withTimestamps();
}

  public function currentPersonals()
{
    return $this->personals(); // без wherePivot
}

   
    // divisions_systems
    public function systems()
    {
        return $this->belongsToMany(System::class, 'divisions_systems', 'division_id', 'system_id');
    }
    public function kndks()
    {
        return $this->belongsToMany(
            Kndk::class,
            'division_kndk',   // pivot-таблиця
            'division_id',     // FK для Division
            'kndk_id'          // FK для Kndk
        )->withTimestamps();
    }


}
