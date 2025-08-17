<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentRelation extends Model
{
     protected $table = 'document_relations';

    protected $fillable = [
        'document_id',
        'relation_type',
        'related_document_id',
    ];

    // Документ, який анульовано/замінено
    public function document()
    {
        return $this->belongsTo(Adocument::class, 'document_id');
    }

    // Новий документ або рішення/акт
    public function relatedDocument()
    {
        return $this->belongsTo(Adocument::class, 'related_document_id');
    }
}
