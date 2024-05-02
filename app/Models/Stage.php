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
        return $this->belongsToMany(Project::class, 'project_stage', 'stage_id', 'project_id');
    }
    // steps
    public function steps()
    {
        return $this->belongsToMany(Step::class, 'stage_step', 'stage_id', 'step_id');
    }
    // personal
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'stage_personal', 'stage_id', 'personal_id')
        ->withPivot('status')
            ->withTimestamps();
    }
    // $table->foreignId('stage_id')->constrained(); $table->foreignId('next_stage_id')->constrained('stages');
    public function nextStages()
    {
        return $this->belongsToMany(Stage::class, 'stage_stage', 'stage_id', 'next_stage_id')
            ->withTimestamps();
    }
    public function previousStages()
    {
        return $this->belongsToMany(Stage::class, 'stage_stage', 'next_stage_id', 'stage_id')
            ->withTimestamps();
    }
}
