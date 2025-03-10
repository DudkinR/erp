<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WANOAREA extends Model
{
    // table name
    protected $table = 'wanoarea';
    // primary key
    public $primaryKey = 'id';
    // timestamps
    public $timestamps = true;
    // fillable fields
    protected $fillable =  ['abv', 'name', 'focus', 'description'];
}
