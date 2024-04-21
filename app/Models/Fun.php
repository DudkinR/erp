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
    // positions
    public function positions()
    {
        return $this->belongsToMany(Position::class, 'positions_functs', 'funct_id', 'position_id');
    }
}
