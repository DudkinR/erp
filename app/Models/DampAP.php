<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DampAP extends Model
{
  protected $table = 'damp_apackages';
  protected $fillable = [
      'damp_date',
       'id_npp',
      'foreign_name',
      'national_name',
  ];
  public $timestamps = false;
  // damp all packeges from date
  public static function getAllPackagesFromDate($date)
{
    return self::where('damp_date', '>=', $date)->get();
}
public static function getUniqueDates()
{
    return self::select('damp_date')
        ->distinct()
        ->orderBy('damp_date', 'asc')
        ->pluck('damp_date'); // поверне колекцію тільки дат
}
}
