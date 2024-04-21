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

    // relationships goal_id to funct_id
    public function funs()
    {
        return $this->belongsToMany(Fun::class, 'goals_functs', 'goal_id', 'funct_id');
    }
}
