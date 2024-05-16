<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // use in docs 
    use HasFactory;
    // table name
    protected $table = 'category';
    // fillable fields
    protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id'];
    // relationship
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    // relationship
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    // relationship docs
    public function docs()
    {
        return $this->belongsToMany(Doc::class, 'category_doc', 'category_id', 'doc_id');
    }
    // relationship images
    public function images()
    {
        return $this->belongsToMany(Image::class, 'category_image');
    }

}
