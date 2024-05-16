<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    use HasFactory;
    // table name
    protected $table = 'documentations';    
    // fillable fields
    protected $fillable = ['name', 'path', 'slug', 'lng', 'link', 'description', 'revision_date', 'publication_date', 'creation_date', 'deletion_date', 'last_change_date', 'last_view_date', 'category_id', 'status'];
    // relationship doc - doc
    public function relatedDocs()
    {
        return $this->belongsToMany(Doc::class, 'doc_doc', 'doc_id', 'related_doc_id');
    }   
    // relationship doc - nomenclature
    public function nomenclatures()
    {
        return $this->belongsToMany(Nomenclature::class, 'numenclature_doc', 'nomenclature_id', 'doc_id');
    }
    // relationship doc - category category_doc belongsToMany
    public function  categories()
    {
        return $this->belongsToMany(Category::class, 'category_doc', 'doc_id', 'category_id');
    }
}
