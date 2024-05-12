<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model
{
    use HasFactory;
    // table name
    protected $table = 'nomenclature';
    protected    $fillable = ['name', 'article', 'description', 'image'];
    // types
    public function types() //  соответствия
    {
        return $this->belongsToMany(Type::class, 'nomenclature_type', 'nomenclature_id', 'type_id');
    }    
    // docs 
    public function docs()
    {
        return $this->belongsToMany(Doc::class, 'nomenclature_doc', 'nomenclature_id', 'doc_id');
    }
    // images
    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_nomenclature', 'nomenclature_id', 'image_id');
    }

}
