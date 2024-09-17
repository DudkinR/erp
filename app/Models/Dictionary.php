<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    use HasFactory;
    // table name
    protected $table = 'dictionary';
    // columns
    protected $fillable = [
        'en',
        'uk',
        'ru',
        'description',
        'example',
        'author',
        'editor'
    ];
}
