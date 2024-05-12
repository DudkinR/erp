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
    public function type() // только одно соответствие
    {
        return $this->belongsTo(Type::class);
    }    
    // docs 
    public function docs()
    {
        return $this->belongsToMany(Doc::class, 'nomenclature_doc', 'nomenclature_id', 'doc_id');
    }

}
