<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magdatafloat extends Model
{
    use HasFactory;
    protected $table = 'magdatafloat';
    protected $fillable = ['data', 'worker_id'];
    
}
