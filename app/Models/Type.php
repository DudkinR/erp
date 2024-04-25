<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    // table name
    protected $table = 'types';
    // fillable fields `name`, `description`, `icon`, `color`, `slug`, `parent_id`
    protected $fillable = ['name', 'description', 'icon', 'color', 'slug', 'parent_id'];
    // parent
    public function parent()
    {
        return $this->belongsTo(Type::class, 'parent_id');
    }
    // children
    public function children()
    {
        return $this->hasMany(Type::class, 'parent_id');
    }

}
