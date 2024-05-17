<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    use HasFactory;
    // table name
    protected $table = 'dimensions';
    
    protected $fillable = [
        'abv',
        'name',
        'kod',
        'description',
        'formula',
        'unit',
        'type',
        'value',
        'min_value',
        'max_value',
        'step',
        'default_value',
        'default_min_value',
        'default_max_value',
        'default_step',
        'default_type',
        'default_unit',
    ];
    // controls
    public function controls()
    {
        return $this->belongsToMany(Control::class, 'control_dimension', 'dimension_id', 'control_id')    
        ->withTimestamps();
    }
    // task
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'dimension_task', 'dimension_id', 'task_id')
        ->withPivot('value', 'fact', 'status', 'comment', 'personal_id')
        ->withTimestamps();
    }   
}
