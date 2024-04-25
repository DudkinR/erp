<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    // table name
    protected $table = 'status';
    // fillable fields
    protected $fillable = ['name', 'description'];
    // relationships personal
    public function personals()
    {
        // personal
        return $this->hasMany( Personal::class, 'status_id', 'id' );
    }
    

}
