<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    // Table name types_connecting
    protected $table = 'types_connecting';
    // Fillable columns
    protected $fillable = [
        'type_id',
        'model',
    ];
    // Relationship to types
    public function types()
    {
        return $this->belongsTo(Type::class);
    }
    // Relationship to models
}
