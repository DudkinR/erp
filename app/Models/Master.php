<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Master extends Model
{
    use HasFactory;
     // author_id', 'text', 'basis', 'who', 'urgency', 'deadline', 'estimate', 'start', 'end', 'done', 'comment', 'created_at', 'updated_at'
   // table 
    protected $table = 'master';
    protected $fillable = [
        'author_id', 'text', 'basis', 'who', 'urgency', 'deadline', 'estimate', 'start', 'end', 'done', 'comment'
    ];

    // docs
    public function docs(){
        return $this->belongsToMany(Doc::class , 'master_doc', 'master_id', 'doc_id');
        
    }
    // personals
    public function personals(){
        return $this->belongsToMany(Personal::class , 'master_personal', 'master_id', 'personal_id');
        
    }
    // sources
    public function resources(){
        return $this->belongsToMany(Resource::class , 'master_resource', 'master_id', 'resource_id');
        
    }
    // briefing
    public function briefing()
    {
        return $this->hasOne(Briefing::class, 'master_id');  // Укажите 'master_id' как внешний ключ
    }

}
