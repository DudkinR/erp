<?php

namespace App\Traits;

use App\Models\Keyword;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasKeywords
{
    public function keywords(): MorphToMany
    {
        return $this->morphToMany(Keyword::class, 'keywordable');
    }
}
