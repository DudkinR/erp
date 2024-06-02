<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;
    // table name
    protected $table = 'goals';
    // primary key
    protected $primaryKey = 'id';
    // fillable fields
    protected $fillable = ['name', 'description', 'due_date', 'completed', 'completed_date', 'status'];

    // relationships objective_goal
    public function objectives()
    {
        return $this->belongsToMany(Objective::class, 'objective_goal', 'goal_id', 'objective_id');
    }
    // relationships objective_goal -> objective_funct
    public function obj_funs()
    {
        return $this->belongsToMany(Objective::class, 'goals_objectives', 'goal_id', 'objective_id')
            ->join('objectives_functs', 'objectives.id', '=', 'objectives_functs.objective_id')
            ->join('functs', 'objectives_functs.funct_id', '=', 'functs.id')
            ->select('functs.id', 'functs.name', 'functs.description');
    }
    public function funs()
    {
        return $this->belongsToMany(Fun::class, 'goals_functs', 'goal_id', 'funct_id');
    }
}
