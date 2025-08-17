<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apackage extends Model
{
    // table name 
    protected $table = 'apackages';
    // fillable attributes
    protected $fillable = [
        
        'foreign_name',
        'national_name',
    ];
    public function documents()
    {
        return $this->belongsToMany(Adocument::class, 'adocument_apackage');
    }
    public function pages(){
       return $this->documents->sum('pages');
    }

}
