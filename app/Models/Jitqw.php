<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jitqw extends Model
{
    use HasFactory;
    // table name
    protected $table = 'jitqws';
    // columns
    protected $fillable = [
        'description_uk',
        'description_ru',
        'description_en',
    ];
    // jits_jitqws
    public function jits()
    {
        return $this->belongsToMany(Jit::class, 'jits_jitqws');
    }
// briefs_jitqws
    public function briefs()
    {
        return $this->belongsToMany(Brief::class, 'briefs_jitqws', 'jitqw_id', 'brief_id');
    }
}
