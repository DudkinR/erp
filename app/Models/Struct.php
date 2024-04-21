<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struct extends Model
{
    use HasFactory;
    // table name
    protected $table = 'structure';
    // fillable
    protected $fillable = ['abv','name', 'description'];
}
