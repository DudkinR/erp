<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    use HasFactory;
    // table name
    protected $table = 'systems';
    // columns
    protected $fillable = ['uk', 'ru', 'en', 'abv', 'group', 'svb'];
    // divisions_systems
    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'divisions_systems', 'system_id', 'division_id');
    }
    
}
