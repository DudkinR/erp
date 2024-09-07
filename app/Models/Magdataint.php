<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magdataint extends Model
{
    use HasFactory;
    protected $table = 'magdataint'; 
    protected $fillable = ['data', 'worker_id'];
}
