<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Brief extends Model
{
    use HasFactory;
    
    protected $table = 'briefs';
    
    protected $fillable = [
        'name_uk',
        'name_ru',
        'name_en',
        'order',
        'type',
        'risk',
        'functional'
    ];

    // Связь с Jitqw
    public function jitqws()
    {
        return $this->belongsToMany(Jitqw::class, 'briefs_jitqws', 'brief_id', 'jitqw_id');
    }
    // Связь с actions
    public function actions()
    {
        return $this->belongsToMany(Type::class, 'briefs_actions', 'brief_id', 'action_id');
    }
    // Связь с reasons
    public function reasons()
    {
        return $this->belongsToMany(Type::class, 'briefs_reasons', 'brief_id', 'reason_id');
    }

    // Связь с Jit через Jitqw


}
