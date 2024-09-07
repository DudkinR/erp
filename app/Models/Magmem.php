<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magmem extends Model
{
    use HasFactory;
    // magmems
    //table name
    protected $table = 'magmems';
    //columns
    protected $fillable = ['name', 'description'];
    //relations
    public function magtables()
    {
        return $this->belongsToMany(Magtable::class)->withPivot('number');
    }
    public function magcolumns()
    {
        return $this->belongsToMany(Magcolumn::class)->withPivot('number');
    }
    
}
