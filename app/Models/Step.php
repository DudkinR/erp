<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;
    // table name
    protected $table = 'steps';
    // name, description
    protected $fillable = [
        'name',
        'description',
    ];
    // stages
    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'step_stage', 'step_id', 'stage_id');
    }
    // controls
    public function controls()
    {
        return $this->belongsToMany(Control::class, 'step_control', 'step_id', 'control_id');
    }
    // personals
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'step_personal', 'step_id', 'personal_id')
        ->withPivot('status')
            ->withTimestamps();
    }
}
