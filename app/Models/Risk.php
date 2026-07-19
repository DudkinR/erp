<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasKeywords;

class Risk extends Model
{
    use HasFactory;
    use HasKeywords;

    protected $table = 'risks';

    protected $fillable = ['name', 'description'];

    /**
     * Зв'язок з класифікатором КНДК СОУ НАЕК
     */
    public function kndks()
    {
        return $this->belongsToMany(
            Kndk::class,
            'kndk_risk',   // Назва pivot-таблиці
            'risk_id',     // FK для Risk
            'kndk_id'      // FK для Kndk
        )->withTimestamps();
    }
    public function experiences()
    {
        return $this->belongsToMany(Experience::class, 'experience_risk');
    }

}

