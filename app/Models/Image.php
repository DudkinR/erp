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
    // problems
    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'problem_image', 'image_id', 'problem_id');
    }
    // categories
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_image', 'image_id', 'category_id');
    }
    // tasks
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'image_task', 'image_id', 'task_id');
    }
}
