<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class FileHelpers extends Helpers
{
    //
    public static function csvToArray($file)
    {
        $csvData = file_get_contents($file->getRealPath(), false, null, 0, 50000); // чтение первых 50,000 байт файла
        $utf8Data = iconv('Windows-1251', 'UTF-8', $csvData); // конвертация в UTF-8
        $csvData = str_getcsv($utf8Data, "\n"); // разбивка на 
        return $csvData;
    }
   
}