<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasKeywords;
class Process extends Model /// вважаємо їх функціями а процес у нас описан кндк
{
    use HasFactory;
    use HasKeywords; 
    /**
     * Атрибути, які можна безпечно масово заповнювати.
     *
     * @var array<int, string>
     */
    protected $fillable = [
         'parent_id', 
        'name',
        'description',
        'type'
    ];

    /**
     * Зв'язок «багато до багатьох» із моделлю Kndk.
     */
    public function kndks(): BelongsToMany
    {
        return $this->belongsToMany(
            Kndk::class,        // Пов'язана модель
            'kndk_process',     // Назва проміжної таблиці (pivot)
            'process_id',       // Зовнішній ключ цієї моделі в pivot-таблиці
            'kndk_id'           // Зовнішній ключ пов'язаної моделі в pivot-таблиці
        )->withTimestamps();    // Автоматично оновлювати created_at/updated_at у pivot
    }
        public function divisions(): BelongsToMany
    {
        return $this->belongsToMany(
            Division::class,       // Пов'язана модель підрозділу
            'division_process',    // Назва нової проміжної таблиці
            'process_id',          // Ключ цієї моделі (Process) у pivot
            'division_id'          // Ключ моделі Division у pivot
        )->withTimestamps();
    }
     public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'document_process')
                    ->withTimestamps(); // щоб автоматично оновлювалися created_at/updated_at у pivot-таблиці
    }
       

    /**
     * Батьківський (вищий) процес.
     */
   public function parent(): BelongsTo
        {
            return $this->belongsTo(Process::class, 'parent_id', 'id')
                        ->withDefault([
                            'id' => 0,
                            'name' => 'Кореневий процес'
                        ]);
        }


    /**
     * Дочірні (підлеглі) процеси першого рівня вкладеності.
     */
    public function children()
    {
        return $this->hasMany(Process::class, 'parent_id');
    }

    /**
     * Рекурсивне отримання всіх підлеглих процесів на всіх рівнях униз.
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Рекурсивний шлях угору (магічний атрибут для збору всіх батьківських процесів).
     */
    public function getAllParentsAttribute()
    {
        $parents = collect();
        
        if ($this->parent && $this->parent->id !== 0) {
            $parents->push($this->parent);
            $parents = $parents->merge($this->parent->all_parents);
        }
        
        return $parents;
    }
}
