<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActionEfficiency extends Model
{
    // table name
    protected $table = 'action_efficiencies';
    // fillable fields
    protected $fillable = [
        'action_id',
        'criterion_id',
        'value_before',
        'value_after',
        'efficiency_index',
        'comment'
    ];
}
