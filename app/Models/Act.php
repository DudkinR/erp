<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    use HasFactory;
    // table name
    protected $table = 'acts';
    // fillable fields
    protected $fillable = [
        'name',
        'description',
        'complite_date',
        'dedline_date',
        'status'
    ];
    // relations
    public function facts()
    {
        return $this->belongsToMany(Fact::class, 'facts_acts');
    }
    
}
