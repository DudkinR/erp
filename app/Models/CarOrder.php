<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarOrder extends Model
{
    use HasFactory;
    //table name
    protected $table = 'carorders';

    //fillable fields
    protected $fillable = [
        'title',
         'description',
            'typecar_id',
            'val',
            'value_type_id',
            'status',
          'start_datetime', 
          'end_datetime', 
          'division_id', 
            'start_point',
            'end_point',
          'hours'];

    //relations
    public function typecar()
    {
        return $this->belongsTo(Type::class, 'typecar_id');
    }
    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }
    public function value_type()
    {
        return $this->belongsTo(Type::class, 'value_type_id');
    }
    // period
    public function period()
    {
        return $this->start_datetime . ' - ' . $this->end_datetime;
    }

}
