<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    // table name
    protected $table = 'images';
    
    protected $fillable = ['name', 'path', 'extension', 'size', 'mime_type', 'url', 'alt', 'title', 'description'];

    // nomenclatures
    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'image_nomenclature', 'image_id', 'nomenclature_id');
    }
}
