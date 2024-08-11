<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;
    // table name
    protected $table = 'objectives';
    // columns
    protected $fillable = ['name', 'description'];

    // goals belongs to many
    public function goals()
    {
        return $this->belongsToMany(Goal::class, 'objective_goal', 'objective_id', 'goal_id');
    }
    // functs belongsToMany
    public function functs()
    {
        return $this->belongsToMany(Fun::class, 'objective_funct', 'objective_id', 'funct_id');
    }
    // objective_objective table relationship parent_objective_id and child_objective_id
    public function children()
    {
        return $this->belongsToMany(Objective::class, 'objective_objective', 'parent_objective_id', 'child_objective_id');
    }
    // objective_objective table relationship parent_objective_id and child_objective_id
    public function parent()
    {
        return $this->belongsToMany(Objective::class, 'objective_objective', 'child_objective_id', 'parent_objective_id');
    }

}
