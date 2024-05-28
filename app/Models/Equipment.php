<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'IDname',
        'name', 
        'description',
        'manufacture_date',
        'expiration_date',
        'verification_date',
        'last_verification_date',
        'next_verification_date',
        'address'
    ];
}
