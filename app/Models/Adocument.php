<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adocument extends Model
{
    // table name
    protected $table = 'adocuments';
    // fillable attributes
    protected $fillable = [

        'foreign_name', // російска або англійська назва
        'national_name', // українська назва
        'doc_type_id', // ID типу документа
        'reg_date', // дата реєстрації
        'pages', // кількість сторінок
        'notes', // номер службової записки
        'production_date', // дата в виробництві
        'kor', // виконавець
        'part', // частина
        'contract', // договор
        'develop', // розробник
        'object', // об'єкт
        'unit', // блок
        'stage', // стадія проекта
        'code', // шифр розробника
        'inventory', // інвентарний номер
        'archive_number', // архівний номер
        'path', // шлях на сервері
        'storage_location', // місце зберігання оригінала
        'status' // статус (діючий / анульований)
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
    public function relatedDocs()
    {
        return $this->belongsToMany(
            Adocument::class,  // зв'язуємо саму модель Adocument
            'doc_doc',         // таблиця
            'doc_id',          // поле в doc_doc яке вказує на цей документ
            'related_doc_id'   // поле на пов'язаний документ
        )
        ->withPivot('type')
        ->wherePivot('type', 'A');
    }
    // back relations doc_doc
    public function replacedBack()
    {
        return $this->belongsToMany(
            Adocument::class,  // зв'язуємо саму модель Adocument
            'doc_doc',         // таблиця
            'related_doc_id',  // поле в doc_doc яке вказує на цей документ
            'doc_id'           // поле на пов'язаний документ
        )
        ->withPivot('type')
        ->wherePivot('type', 'A');
    }

}
