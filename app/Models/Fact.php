<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
    use HasFactory;
    // table name
    protected $table = 'facts';
    // fillable fields
    protected $fillable = [
        'name',
        'description',
        'image',
        'status'
    ];
    // relations
    public function criterias()
    {
        return $this->belongsToMany(Criteria::class, 'criterias_facts');
    }
    public function acts()
    {
        return $this->belongsToMany(Act::class, 'facts_acts');
    }
    public function images()
    {
        return $this->belongsToMany(Image::class, 'fact_image', 'fact_id', 'image_id');
    }

}
