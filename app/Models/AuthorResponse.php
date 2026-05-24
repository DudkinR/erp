<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorResponse extends Model
{
    protected $table = 'author_responses';

    protected $fillable = [
        'inconsistency_id',
        'user_id',
        'response_text',
        'is_fixed',
    ];

    /**
     * Зв'язок з невідповідністю
     */
    public function inconsistency(): BelongsTo
    {
        return $this->belongsTo(Inconsistency::class, 'inconsistency_id');
    }

    /**
     * Зв'язок з користувачем (автором)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
