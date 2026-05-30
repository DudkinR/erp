<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Process extends Model /// вважаємо їх функціями а процес у нас описан кндк
{
    use HasFactory;

    /**
     * Атрибути, які можна безпечно масово заповнювати.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
}
