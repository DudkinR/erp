<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    use HasFactory;
    // use table name
    protected $table = 'problems';
    // use  fillable
    protected $fillable = [
        'name', 'description', 'priority', 'date_start', 'date_end', 'deadline', 'status', 'project_id', 'stage_id', 'step_id', 'control_id'

    ];
    // personals  problem_personal
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'problem_personal', 'problem_id', 'personal_id')
        ->withPivot('view', 'comment');
    }

}
