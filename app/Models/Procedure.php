<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Procedure extends Model
{
   use HasFactory;

    // Яка таблиця використовується (Laravel сам здогадається, але можна явно вказати)
    protected $table = 'procedures';

    // Поля, які можна масово заповнювати (fillable)
    protected $fillable = [
        'name',
        'description',
    ];
    public function steps()
    {
        return $this->hasMany(ProcedureStep::class);
    }

}
