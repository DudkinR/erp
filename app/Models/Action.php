<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    // table name
    protected $table = 'actions';
    // fillable fields
    protected $fillable = [
        'proposal_id',
        'title',
        'responsible',
        'deadline',
        'status',
        'result_description'
    ];
     //   $efficiency_criteria = $consideration->efficiency_criteria()->get();
    public function efficiency_criteria()
    {
        return $this->hasMany(EfficiencyCriterion::class ,'action_id');
    }
}
