<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Objct extends Model
{
    protected $table = 'objects';

    protected $fillable = [
        'name',   // назва точки
        'x',      // координата X
        'y',      // координата Y
    ];

    // Маршрути, де ця точка використовується як відправлення
    public function routesFrom()
    {
        return $this->hasMany(Taxiroute::class, 'from_id');
    }

    // Маршрути, де ця точка використовується як призначення
    public function routesTo()
    {
        return $this->hasMany(Taxiroute::class, 'to_id');
    }
}

