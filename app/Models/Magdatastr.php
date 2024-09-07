<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magdatastr extends Model
{
    use HasFactory;
    protected $table = 'magdatastr';
    protected $fillable = ['data', 'worker_id'];
}
