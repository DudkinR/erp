<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasKeywords;

class Kndk extends Model
{
    use HasFactory;
    use HasKeywords; // Тепер документ може мати ключові слова

    /**
     * Поля, які можна масово заповнювати.
     */
       protected $table = 'kndks'; 
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

    public function processes(): BelongsToMany
    {
        return $this->belongsToMany(
            Process::class,     // Пов'язана модель
            'kndk_process',     // Назва проміжної таблиці (pivot)
            'kndk_id',          // Зовнішній ключ цієї моделі в pivot-таблиці
            'process_id'        // Зовнішній ключ пов'язаної моделі в pivot-таблиці
        )->withTimestamps();    // Автоматично оновлювати created_at/updated_at у pivot
    }

    public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(
            Division::class,      // Пов'язана модель підрозділу
            'division_kndk',      // Назва вашої проміжної (pivot) таблиці
            'kndk_id',            // Зовнішній ключ моделі Kndk у pivot-таблиці
            'division_id'         // Зовнішній ключ моделі Division у pivot-таблиці
        )->withTimestamps();
    }

    /**
     * Зв'язок із ВИКОНАВЦЯМИ (таблиця kndk_position)
    * Єдиний базовий зв'язок з посадами через таблицю kndk_position.
     * Обов'язково додаємо withPivot('role'), щоб Laravel бачив стовпець ролі.
     */
    public function positions(): BelongsToMany
    {
        return $this->belongsToMany(Position::class, 'kndk_position', 'kndk_id', 'position_id')
                    ->withPivot('role') 
                    ->withTimestamps();
    }

    // =========================================================================
    // ДИНАМІЧНІ РОЛІ (Точно під ваш код збереження)
    // =========================================================================

    /**
  
     * Власники (owners)
     */
    public function owners(): BelongsToMany 
    { 
        return $this->positions()
                    ->wherePivot('role', 'owner')
                    ->withPivot('division_id', 'role'); // Додали division_id у pivot
    } 

    /**
     * Виконавці / Учасники (executors)
     */
    public function executors(): BelongsToMany 
    { 
        return $this->positions()
                    ->wherePivot('role', 'executor')
                    ->withPivot('division_id', 'role'); 
    } 

    /**
     * Відповідальні (responsibles)
     */
    public function responsibles(): BelongsToMany 
    { 
        // Виправлено: роль змінено з 'executor' на 'responsible'
        return $this->positions()
                    ->wherePivot('role', 'responsible') 
                    ->withPivot('division_id', 'role'); 
    }

}
