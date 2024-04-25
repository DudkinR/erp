<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Control extends Model
{
    use HasFactory;
    // table name
    protected $table = 'controls';
    // name, description
    protected $fillable = [
        'name',
        'description',
    ];
    // steps
    public function steps()
    {
        return $this->belongsTo(Step::class, 'step_control', 'control_id', 'step_id')
        ->withPivot('performance', 'control_date', 'control_result')
            ->withTimestamps();
    }
    // personals
    public function personals()
    {
        return $this->belongsTo(Personal::class, 'control_personal', 'control_id', 'personal_id')
        ->withPivot('status')
            ->withTimestamps();
    }
}
