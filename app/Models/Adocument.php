<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adocument extends Model
{
    // table name
    protected $table = 'adocuments';
    // fillable attributes
    protected $fillable = [
       
        'foreign_name',
        'national_name',
        'doc_type_id',
        'reg_date',
        'pages',
        'production_date',
        'kor',
        'part',
        'contract',
        'develop',
        'object',
        'unit',
        'stage',
        'code',
        'inventory',
        'path',
        'storage_location',
        'status'
    ];
    public function packages()
    {
        return $this->belongsToMany(Apackage::class, 'adocument_apackage');
    }

    public function documentRelations() // Відношення документів
    {
        return $this->hasMany(DocumentRelation::class, 'document_id');
    }

    public function replacedBy() // Документ, який замінює
    {
        return $this->hasOne(DocumentRelation::class, 'document_id')
                    ->where('relation_type', 'replaced_by');
    }

    public function canceledBy() // Документ, який анульовано
    {
        return $this->hasOne(DocumentRelation::class, 'document_id')
                    ->where('relation_type', 'canceled_by');
    }

}
