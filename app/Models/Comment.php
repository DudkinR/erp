<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $table = 'comments';
    protected $fillable = ['comment'];
    // personal_comment
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'personal_comment', 'comment_id', 'personal_id');
    }
    // task_comment
    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_comment', 'comment_id', 'task_id');
    }
}
