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
        'division',
        'min',
        'max',
    ];

   
   
 
}
