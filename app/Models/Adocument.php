<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adocument extends Model
{
    // table name
    protected $table = 'adocuments';
    // fillable attributes
    protected $fillable = [
        'foreign_name',
        'national_name',
        'reg_date',
        'production_date',
        'kor',
        'part',
        'contract',
        'develop',
        'object',
        'unit',
        'stage',
        'code',
        'inventory',
        'path',
        'storage_location',
    ];
    public function packages()
    {
        return $this->belongsToMany(Apackage::class, 'adocument_apackage');
    }
    
}
