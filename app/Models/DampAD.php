<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DampAD extends Model
{

  protected $table = 'damp_adocuments';
  protected $fillable = [
      
      'foreign_name',
      'national_name',
      'reg_date',
      'pages',
      'doc_type_id', 
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
      'status',
      'damp_date',
        'id_npp',
        'package_id'
  ];

  // without updated_at
  public $timestamps = false;

  // package relationship
  public function package()
{
    return $this->belongsTo(DampAP::class, 'package_id');
}
  // packages relationship
 public function packages()
    {
        return $this->belongsToMany(DampAP::class, 'adocument_apackage', 'document_id', 'package_id');
    }


  // damp all documents from date
  public static function getAllDocumentsFromDate($date)
{
    return self::where('damp_date', '>=', $date)
    ->with('package')
    ->get();

}



}
