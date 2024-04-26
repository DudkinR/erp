<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    // table name
    protected $table = 'clients';
    protected $fillable = ['name', 'business_region', 'registration_date', 'code'];
    

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_client', 'client_id', 'project_id')
        ->withPivot('status');
    }

}
