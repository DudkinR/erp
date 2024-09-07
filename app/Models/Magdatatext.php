<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magdatatext extends Model
{
    use HasFactory;
    protected $table = 'magdatatext';
    protected $fillable = ['data', 'worker_id'];
    
}
