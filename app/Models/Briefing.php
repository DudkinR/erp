<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefing extends Model
{
    use HasFactory;
    protected $table = 'briefing';

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'briefing_personal', 'briefing_id', 'personal_id');
    }
}
