<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magtable extends Model
{
    use HasFactory;
    // magtables
    //table name
    protected $table = 'magtables';
    //columns
    protected $fillable = ['name', 'description'];
    //relations
    public function magcolumns()
    {
        return $this->belongsToMany(Magcolumn::class, 'magtable_magcolumn' , 'magtable_id' , 'magcolumn_id'
        )->withPivot('number');
    }
    public function magmems()
    {
        return $this->belongsToMany(Magmem::class, 'magtable_magmem' , 'magtable_id' , 'magmem_id'
        )->withPivot('number');
    }
    // magtable_division
    public function divisions()
    {
        
        return $this->belongsToMany(Division::class , 'magtable_division' , 'magtable_id' , 'division_id'
        )->withPivot('type');
    }
}
