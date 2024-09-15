<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    // table
    protected $table = 'forms'; 
    // fillable
    protected $fillable = ['name', 'description', 'status', 'author_tn'];

    // items
    public function items()
    {
        return $this->belongsToMany(Item::class, 'form_item', 'form_id', 'item_id')->withPivot('order', 'status', 'author_tn')->withTimestamps();
    }
    // divisions
    public function divisions()
    {
        return $this->belongsToMany(Division::class, 'form_division', 'form_id', 'division_id')->withTimestamps();
    }
    
}
