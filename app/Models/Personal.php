<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;
    // table name
    protected $table = 'personal';

    // fillable fields
    protected $fillable = ['tn', 'nickname', 'fio', 'email', 'phone'];

    // relationships
    public function positions()
    {
        // personal_position
        return $this->belongsToMany( Position::class, 'personal_position', 'personal_id', 'position_id' );
    }




}
