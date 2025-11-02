<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    // 
    protected $table = 'proposals';
    protected $fillable = [
        'division_id',
        'title',
        'description',
        'proposal',
        'status',
        'decision'
    ];



    public function actions()
    {
        return $this->hasMany(Action::class, 'proposal_id');
    }  

}
