<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    use HasFactory;
    // author_id', 'text', 'basis', 'who', 'urgency', 'deadline', 'estimate', 'start', 'end', 'done', 'comment', 'created_at', 'updated_at'
    // table 
    protected $table = 'master';
    protected $fillable = [
        'author_id', 'text', 'basis', 'who', 'urgency', 'deadline', 'estimate', 'start', 'end', 'done', 'comment'
    ];

    // docs
    public function docs(){
        
    }
}
