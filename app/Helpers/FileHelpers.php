<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class FileHelpers extends Helpers
{
    //
    public static function csvToArray($file)
    {
        $csvData = file_get_contents($file->getRealPath(), false, null, 0, 5000000); // чтение первых 50,000 байт файла
    //  $utf8Data = iconv('Windows-1251', 'UTF-8//IGNORE', $csvData);
       $utf8Data = $csvData ;
//
      //  $utf8Data = iconv('Windows-1251', 'UTF-8', $csvData); // конвертация в UTF-8
        $csvData = str_getcsv($utf8Data, "\n"); // разбивка на 
        $csvData = array_slice($csvData, 1); // Удаляем первый элемент массива

        return $csvData;
    }
   
}