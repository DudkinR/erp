<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    // table name 
    protected $table = 'tasks';
    // fillable fields `id`, `project_id`, `stage_id`, `step_id`, `dimension_id`, `control_id`, `deadline_date`, `status`, `responsible_position_id`, `dependent_task_id`, `parent_task_id`, `real_start_date`, `real_end_date`, `created_at`, `updated_at`
    protected $fillable = ['project_id', 'stage_id', 'step_id', 'dimension_id', 'control_id', 'deadline_date', 'status', 'responsible_position_id', 'dependent_task_id', 'parent_task_id', 'real_start_date', 'real_end_date','count', 'order' , 'type'];
    // relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    //stage
    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }
    //step
    public function step()
    {
        return $this->belongsTo(Step::class);
    }
    //parent_task_id
    public function parentTask()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }
    // nomenclature
    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'nomenclature_task', 'task_id', 'nomenclature_id');
    }
    //structures
    public function structures()
    {
        return $this->belongsToMany(Struct::class, 'struct_task', 'task_id', 'struct_id');
    }
    //dimensions
    public function dimensions()
    {
        return $this->belongsToMany(Dimension::class, 'dimension_task', 'task_id', 'dimension_id')
        ->withPivot('value', 'fact', 'status', 'comment', 'personal_id')
        ->withTimestamps();
    }
    // images
    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_task', 'task_id', 'image_id');
    }
    // task_task table relationship parent_task_id and child_task_id
    public function children()
    {
        return $this->belongsToMany(Task::class, 'task_task', 'parent_task_id', 'child_task_id');
    }
    // task_task table relationship parent_task_id and child_task_id
    public function parents()
    {
        return $this->belongsToMany(Task::class, 'task_task', 'child_task_id', 'parent_task_id');
    }
}
