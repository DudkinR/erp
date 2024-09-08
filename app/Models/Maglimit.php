<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maglimit extends Model
{
    use HasFactory;
    // maglimits
    //table name
    protected $table = 'maglimits';
    //columns `id`, `hfb`, `hfb_doc_id`, `heb`, `heb_doc_id`, `hrb`, `hrb_doc_id`, `hwb`, `hwb_doc_id`, `lwb`, `lwb_doc_id`, `lrb`, `lrb_doc_id`, `leb`, `leb_doc_id`, `lfb`, `lfb_doc_id`
    protected $fillable = [
        'hfb',     
        'hfb_doc_id', 
        'heb', 
        'heb_doc_id', 
        'hrb', 
        'hrb_doc_id', 
        'hwb', 
        'hwb_doc_id', 
        'lwb', 
        'lwb_doc_id', 
        'lrb',
        'lrb_doc_id',
        'leb',
        'leb_doc_id',
        'lfb',
        'lfb_doc_id'
    ];

}
