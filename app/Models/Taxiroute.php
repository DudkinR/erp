<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taxiroute extends Model
{
    protected $table = 'taxiroutes';

    protected $fillable = [
        'from_id',        // ID точки відправлення
        'to_id',        // назва точки призначення
        'date',           // дата рейсу
        'time',           // час рейсу
        'car_id',         // зв'язок з машиною
    ];

    // Зв'язок: маршрут належить одній машині
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
    //division
    public function division()
    {
        return $this->belongsTo(Division::class);
    }
    
    // звязок з пасажирами
    
    public function passengers()
    {
        return $this->belongsToMany(User::class, 'passenger_taxiroutes', 'taxiroute_id', 'user_id')
                    ->withPivot('status') // статус пасажира на цьому маршруті (наприклад, "заброньовано", "підтверджено", "відмінено")
                    ->withTimestamps();
    }
    //from
    public function from()
    {
        return $this->belongsTo(Objct::class, 'from_id');
    }
    //to
    public function to()
    {
        return $this->belongsTo(Objct::class, 'to_id');
    }
}