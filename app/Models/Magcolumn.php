<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magcolumn extends Model
{
    use HasFactory;
    // magcolumns
    //table name
    protected $table = 'magcolumns';
    //columns
    protected $fillable = ['name', 'description', 'dimensions', 'type'];

    //relations
    public function magtables()
    {
        return $this->belongsToMany(Magtable::class)->withPivot('number');
    }
    public function magmems()
    {
        return $this->belongsToMany(Magmem::class)->withPivot('number');
    }
// maglimits
    public function maglimits()
    {
        return $this->belongsToMany(Maglimit::class,'magcolumns_maglimits' , 'magcolumn_id' , 'maglimit_id'        );
    }
    public function magdatabools()
    {
        return $this->belongsToMany(Magdatabool::class,'mag_column_databool' , 'magcolumn_id' , 'magdatabool_id'     );
    }
    public function magdatafloats()
    {
        return $this->belongsToMany(Magdatafloat::class,'mag_column_datafloat' , 'magcolumn_id' , 'magdatafloat_id'     ); 
    }
    public function magdataints()
    {
        return $this->belongsToMany(Magdataint::class , 'mag_column_dataint' , 'magcolumn_id' , 'magdataint_id'     );
    }
    public function magdatatexts()
    {
        return $this->belongsToMany(Magdatatext::class,'mag_column_datatext' , 'magcolumn_id' , 'magdatatext_id'     );
    }
    public function magdatastrs()
    {
        return $this->belongsToMany(Magdatastr::class,'mag_column_datastr' , 'magcolumn_id' , 'magdatastr_id'     );
    }
    public function magdatatimes()
    {
        return $this->belongsToMany(Magdatatime::class,'mag_column_datatime' , 'magcolumn_id' , 'magdatatime_id'     );
    }

}
