<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;
    // table name
    protected $table = 'criterias';
    // fillable fields
    protected $fillable = [
        'name',
        'description',
        'native',
        'foreign'
    ];
    // relations
    public function facts()
    {
        return $this->belongsToMany(Fact::class, 'criterias_facts');
    }
    
}
