<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
  
    // Table name is automatically 'words', so no need to specify unless different
    protected $fillable = [
        'bedword',
        'comment',
        'type',
    ];

    /**
     * Relationships
     */

    // Many-to-many with User through word_user pivot
 public function users()
{
    return $this->belongsToMany(User::class, 'word_user')->withTimestamps();
}


}
