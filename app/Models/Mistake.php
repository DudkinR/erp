<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mistake extends Model
{
    use HasFactory;
     protected $table = 'mistakes';
    protected $fillable = ['user_id', 'text'];
    // masters
    public function masters(){
        return $this->belongsToMany(Master::class , 'master_mistake', 'mistake_id', 'master_id');
        
    }
}
