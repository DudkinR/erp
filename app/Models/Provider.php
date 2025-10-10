<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    // Назва таблиці (необов'язково, якщо назва моделі відповідає назві таблиці)
    protected $table = 'providers';

    // Масив атрибутів, які дозволено масово заповнювати
    protected $fillable = [
        'full_name',
        'short_name',
        'ownership_form',
        'edrpou_code',
        'country',
        'products_services',
        'decision_number',
        'decision_date',
        'valid_until',
        'notes',
        'status',
    ];

    // Якщо потрібно, можна додати кастинг для дат
    protected $casts = [
        'decision_date' => 'date',
        'valid_until' => 'date',
    ];
}
