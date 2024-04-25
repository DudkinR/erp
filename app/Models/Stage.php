<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;
    // table name
    protected $table = 'stages';
    // name, description
    protected $fillable = [
        'name',
        'description',
    ];
    // projects
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_stage', 'stage_id', 'project_id')
        ->withPivot('performance', 'control_date', 'control_result')
            ->withTimestamps();
    }
    // steps
    public function steps()
    {
        return $this->belongsTo(Step::class, 'step_stage', 'stage_id', 'step_id')
        ->withPivot('performance', 'control_date', 'control_result')
            ->withTimestamps();
    }
    // personal
    public function personals()
    {
        return $this->belongsTo(Personal::class, 'stage_personal', 'stage_id', 'personal_id')
        ->withPivot('status')
            ->withTimestamps();
    }
}
