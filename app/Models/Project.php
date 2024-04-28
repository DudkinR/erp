<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
     // table name
    protected $table = 'projects';
    protected $fillable = [
        'name',
        'description',
        'priority',
        'number',
        'date',
        'amount',
        'client',
        'current_state',
        'execution_period',
        'payment_percentage',
        'shipping_percentage',
        'debt_percentage',
        'currency',
        'operation',
    ]; 
    // stages
    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'project_stage', 'project_id', 'stage_id');
    } 
    // personal
    public function personals()
    {
        return $this->belongsToMany(Personal::class, 'project_personal', 'project_id', 'personal_id')
            ->withPivot('status')
            ->withTimestamps();
    }  
    // clients
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'project_client', 'project_id', 'client_id')
            ->withPivot('status')
            ->withTimestamps();
    }
}
