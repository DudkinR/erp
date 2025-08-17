<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocType extends Model
{
    //
    protected $table = 'doc_types';
    protected $fillable = ['old_id', 'foreign_name', 'national_name'];
    
}
