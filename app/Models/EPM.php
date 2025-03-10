<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EPM extends Model
{
    // table name
    protected $table = 'epm';    
    // fillable fields
    protected $fillable = [
        'name', 
        'description',
        'area',
        'divition'
    ];
    // wano  relationship with WANOAREA epm_area = wanoarea_id
    public function wanoarea()
    {        
        return $this->belongsTo('App\Models\WANOAREA', 'epm_area', 'wanoarea_id');   
    }
    // division relationship with DIVISION epm_division = division_id
    public function div()
    {        
        return $this->belongsTo('App\Models\DIVISION', 'epm_divition', 'division_id');   
    }
 
}
