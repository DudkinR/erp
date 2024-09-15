<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fun extends Model
{
    use HasFactory;
    // table
    protected $table = 'functs';
    // fillable
    protected $fillable = ['name','description'];
    // relationships goal_id to funct_id
    public function goals()
    {
        return $this->belongsToMany(Goal::class, 'goals_functs', 'funct_id', 'goal_id');
    }   
    // objectives
    public function objectives()
    {
        return $this->belongsToMany(Objective::class, 'objective_funct', 'funct_id', 'objective_id');
    }
    // positions_functs  `position_id`, `division_id`, `funct_id`
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'positions_functs', 'funct_id', 'position_id' )->withPivot('division_id');
    }
}
