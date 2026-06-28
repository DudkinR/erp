<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasKeywords;
class Document extends Model
{
    use HasKeywords; // Тепер документ може мати ключові слова
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

    /**
     * Процеси, до яких відноситься цей документ.
     */
    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(Process::class, 'document_process')
                    ->withTimestamps();
    }

    /**
     * Отримати вищі (батьківські) документи.
     * Це документи, які містять процеси, що є батьківськими для процесів поточного документа.
     */
    public function getDynamicParentsAttribute()
    {
        // 1. Беремо ID усіх процесів поточного документа
        $processIds = $this->processes->pluck('id');

        // 2. Знаходимо ID їхніх батьківських процесів
        $parentProcessIds = Process::whereIn('id', $processIds)
            ->where('parent_id', '!=', 0)
            ->pluck('parent_id')
            ->unique();

        if ($parentProcessIds->isEmpty()) {
            return collect();
        }

        // 3. Знаходимо документи, які жорстко прив'язані до цих батьківських процесів
        return Document::whereHas('processes', function ($query) use ($parentProcessIds) {
            $query->whereIn('processes.id', $parentProcessIds);
        })->where('inv_no', '!=', $this->inv_no)->get();
    }

}
