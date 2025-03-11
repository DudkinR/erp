<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EPMdata extends Model
{
    //
    protected $table = 'epmdata';
    protected $fillable = [
        'epm_id',
        'value',
        'date_received',
        'date_entered',
        'blocked',
        'user_id'
    ];
    public function epm()
    {
        return $this->belongsTo('App\Models\EPM', 'epm_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
    
}
