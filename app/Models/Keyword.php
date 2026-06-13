<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
     protected $fillable = ['name'];

    // Отримання всіх документів, які мають це ключове слово
    public function documents(): MorphToMany
    {
        return $this->morphedByMany(Document::class, 'keywordable');
    }

    // Отримання всіх процесів, які мають це ключове слово
    public function processes(): MorphToMany
    {
        return $this->morphedByMany(Process::class, 'keywordable');
    }
    // Отримання всіх kndks, які мають це ключове слово
    public function kndks(): MorphToMany
    {
        return $this->morphedByMany(Kndk::class, 'keywordable');
    }
}
