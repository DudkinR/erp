<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EfficiencyCriterion extends Model
{
    
    protected $table = 'efficiency_criteria';
    protected $fillable = [
        'proposal_id',
        'action_id',
        'name',
        'weight',
        'unit'
    ];
    // action
    public function action()
    {
        return $this->belongsTo(\App\Models\Action::class);
    }
    // proposal
    public function proposal()
    {
        return $this->belongsTo(\App\Models\Proposal::class);
    }

}

