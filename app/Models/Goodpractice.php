<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goodpractice extends Model
{
    use HasFactory;
        protected $table = 'goodpractices';
    protected $fillable = ['user_id', 'text'];
    // masters
    public function masters(){
        return $this->belongsToMany(Master::class , 'master_goodpractice', 'goodpractice_id', 'master_id');
        
    }
}
