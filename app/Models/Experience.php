<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    // table name
    protected $table = 'experiences';
    // fillable
    protected $fillable = ['text_uk','text_ru','text_en',  'npp', 'year', 'consequence', 'accepted', 'author_tn'];

    // systems relation experiences_systems
    public function systems()
    {
        return $this->belongsToMany(System::class, 'experiences_systems', 'experience_id', 'system_id');    
    }
    // types relation experiences_actions
    public function actions()
    {
        return $this->belongsToMany(Type::class, 'experiences_actions', 'experience_id', 'action_id');
    }  
    // types relation experiences_equipments 
    public function equipments()
    {
        return $this->belongsToMany(Type::class, 'experiences_equipments', 'experience_id', 'equipment_id');
    }
    // user reasons relation experiences_reasons
    public function reasons()
    {
        return $this->belongsToMany(Type::class, 'experiences_reasons', 'experience_id', 'reason_id');
    }

}
