<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brief extends Model
{
    use HasFactory;
    // table name
    protected $table = 'briefs';
    // columns
    //`id`, `name_uk`, `name_ru`, `name_en`, `order`, `type`, `risk`, `functional`, `created_at`, `updated_at`
    protected $fillable = [
        'name_uk',
        'name_ru',
        'name_en',
        'order',
        'type',
        'risk',
        'functional'
    ];
    // relationships jitqws
    public function jitqws()
    {
        return $this->belongsToMany(Jitqw::class, 'briefs_jitqws' , 'brief_id', 'jitqw_id');
        
    }

}
