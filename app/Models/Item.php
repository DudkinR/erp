<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    // table
    protected $table = 'items';
    // fillable
    protected $fillable = ['text', 'status', 'author_tn'];

    // forms
    public function forms()
    {
        return $this->belongsToMany(Form::class, 'form_item', 'item_id', 'form_id')->withPivot('order', 'status', 'author_tn')->withTimestamps();
    }   
}
