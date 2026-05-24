<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kndk extends Model
{
    use HasFactory;

    /**
     * Поля, які можна масово заповнювати.
     */
    protected $fillable = [
        'class',
        'subclass',
        'group',
        'full_code',
        'name',
        'object_type',
    ];

    /**
     * Автоматичне приведення типів для стовпчиків.
     */
    protected $casts = [
        'class' => 'integer',
        // subclass та group залишаємо string, щоб не губилися нулі на початку (напр., '05')
    ];

    // =========================================================================
    // SCOPES (Методи швидкої фільтрації для запитів)
    // =========================================================================

    /**
     * Отримати тільки перший рівень (Класи).
     * Виклик: Kndk::classes()->get();
     */
    public function scopeClasses(Builder $query): Builder
    {
        return $query->whereNull('subclass')->whereNull('group');
    }

    /**
     * Отримати тільки другий рівень (Підкласи).
     * Виклик: Kndk::subclasses()->get();
     */
    public function scopeSubclasses(Builder $query): Builder
    {
        return $query->whereNotNull('subclass')->whereNull('group');
    }

    /**
     * Отримати тільки третій рівень (Групи).
     * Виклик: Kndk::groups()->get();
     */
    public function scopeGroups(Builder $query): Builder
    {
        return $query->whereNotNull('subclass')->whereNotNull('group');
    }

    /**
     * Отримати всі підкласи для конкретного класу.
     * Виклик: Kndk::ofClass(1)->subclasses()->get();
     */
    public function scopeOfClass(Builder $query, int $classId): Builder
    {
        return $query->where('class', $classId);
    }

    // =========================================================================
    // ЗРУЧНІ МЕТОДИ КЛАСУ (Helpers)
    // =========================================================================

    /**
     * Перевірити, чи є поточний запис верхнім рівнем (класом).
     * Виклик: if ($item->isClass()) { ... }
     */
    public function isClass(): bool
    {
        return is_null($this->subclass) && is_null($this->group);
    }

    /**
     * Визначити поточний рівень ієрархії цифрой (1, 2 або 3).
     */
    public function getLevelAttribute(): int
    {
        if ($this->isClass()) return 1;
        if (is_null($this->group)) return 2;
        return 3;
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(
            Document::class,      // Связанная модель
            'document_kndk',      // Имя промежуточной таблицы
            'kndk_id',            // Внешний ключ этой модели в промежуточной таблице
            'document_inv_no',    // Внешний ключ модели Document в промежуточной таблице
            'id',                 // Локальный ключ этой модели
            'inv_no'              // Локальный ключ модели Document
        )->withTimestamps();
    }
    
}
