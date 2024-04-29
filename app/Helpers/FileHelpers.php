<?php
namespace App\Helpers;

use Illuminate\Support\Str;

class FileHelpers extends Helpers
{
    //
    public static function csvToArray($file,$type_of_file=0)
    {
        $csvData = file_get_contents($file->getRealPath(), false, null, 0, 5000000); // чтение первых 50,000 байт файла
        if($type_of_file==0){
            $utf8Data = iconv('Windows-1251', 'UTF-8//IGNORE', $csvData); // конвертация в UTF-8
        }else{
            $utf8Data = $csvData ;
        }
        $csvData = str_getcsv($utf8Data, "\n"); // разбивка на 
        $csvData = array_slice($csvData, 1); // Удаляем первый элемент массива

        return $csvData;
    }
   
}