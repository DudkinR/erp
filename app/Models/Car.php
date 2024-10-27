<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    // table
    protected $table = 'cars';
    // fillable
    protected $fillable = [
        'name',
        'type_id',
        'gov_number',
        'condition_id',

    ];
    // without timestamps
    public $timestamps = false;
    // type
    public function type()
    {
        return $this->belongsTo(Type::class);
    }
    // condition
    public function condition()
    {
        return $this->belongsTo(Type::class);
    }
    // driver
    public function drivers()
    {
        return $this->belongsToMany(Position::class, 'cars_drivers' , 'car_id', 'driver_id')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }


}
