<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProcedureStep extends Model
{
    use HasFactory;

    protected $table = 'procedure_steps';

    protected $fillable = [
        'procedure_id',   // зв’язок з процедурою
        'problem',        // опис проблеми
        'solution',       // як виправити
        'copy_text',      // текст для копіювання
        'is_loop',        // чи це внутрішній цикл
        'is_end',         // чи це кінець процедури
        'order',          // порядок виконання
    ];

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }


}
