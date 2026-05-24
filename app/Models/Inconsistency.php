<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inconsistency extends Model
{
    protected $table = 'inconsistencs';

    protected $fillable = [
        'point',
        'current_text',
        'proposed_text',
        'reason',
        'qa_text',
        'qa_confirmation',
        'is_fixed',
        'status',
    ];

    /**
     * Зв'язок з документами (багато до багатьох)
     */
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,
            'document_inconsistency',
            'inconsistency_id',
            'document_inv_no',
            'id',
            'inv_no'
        )->withTimestamps();
    }

    /**
     * Зв'язок з користувачами (багато до багатьох)
     * role: creator, qa, author
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'inconsistency_user',
            'inconsistency_id',
            'user_id'
        )->withPivot('role')->withTimestamps();
    }

    /**
     * Відповіді автора (один до багатьох)
     */
    public function authorResponses(): HasMany
    {
        return $this->hasMany(AuthorResponse::class, 'inconsistency_id', 'id');
    }
    // comments
    public function comments(): BelongsToMany
    {
        return $this->belongsToMany(Comment::class, 'inconsistency_comment', 'comment_id', 'inconsistency_id')->withTimestamps();
    }
}
