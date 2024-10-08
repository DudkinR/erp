<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jit extends Model
{
    use HasFactory;

    // table name
    protected $table = 'jits';
    // columns
    protected $fillable = [
        'name_uk',
        'description_uk',
        'name_ru',
        'description_ru',
        'name_en',
        'description_en',
        'keywords',
        'num'
    ];
 // jits_jitqws
    public function jitqws()
    {
        return $this->belongsToMany(Jitqw::class, 'jits_jitqws');
    }   

}
