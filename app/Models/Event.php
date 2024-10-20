<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $table = 'events';
     protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status', 'control_date'];
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'events_projects', 'event_id', 'project_id')
            ->withPivot('division_id', 'position_id');
    }
    public function steps()
    {
        return $this->belongsToMany(Step::class, 'events_steps', 'event_id', 'step_id')
            ->withPivot('position_id', 'division_id');
    }
}
