<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magdatatime extends Model
{
    use HasFactory;
    protected $table = 'magdatatime';
    protected $fillable = ['data', 'worker_id'];
    
}
