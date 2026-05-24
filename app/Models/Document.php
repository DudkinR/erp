<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    protected $table = 'documents';
    protected $primaryKey = 'inv_no';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'inv_no', 'doc_type', 'code', 'organization', 'short_content',
        'date_reg', 'date_start', 'date_end', 'distribution', 'replaced_content',
        'replaced_by', 'change_no', 'page_count', 'note', 'storage_location',
        'registration_date', 'is_cancelled', 'cancellation_date', 'is_reissued',
        'author', 'approved_by', 'project'
    ];

    /**
     * Зв'язок з КНДК (як у тебе вже є)
     */
    public function kndks(): BelongsToMany
    {
        return $this->belongsToMany(
            Kndk::class,
            'document_kndk',
            'document_inv_no',
            'kndk_id',
            'inv_no',
            'id'
        )->withTimestamps();
    }

    /**
     * Зв'язок з невідповідностями (багато до багатьох)
     */
    public function inconsistencies(): BelongsToMany
    {
        return $this->belongsToMany(
            Inconsistency::class,
            'document_inconsistency',   // назва pivot таблиці
            'document_inv_no',          // зовнішній ключ для Document
            'inconsistency_id',         // зовнішній ключ для Inconsistency
            'inv_no',                   // локальний ключ Document
            'id'                        // локальний ключ Inconsistency
        )->withTimestamps();
    }
}
