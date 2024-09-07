<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magdatabool extends Model
{
    use HasFactory;
    protected $table = 'magdatabool';
    protected $fillable = ['data', 'worker_id'];
}
