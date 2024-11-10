<?php
namespace App\Helpers;

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
    public static function processAndStoreImage(UploadedFile $file, $path = 'callings')
    {
        // Перевіряємо, чи файл завантажився
        if (!$file->isValid()) {
            return null;
        }

        // Завантажуємо зображення з файлу
        $image = Image::make($file->getRealPath());

        // Змінюємо формат на .png
        $image->encode('png');

        // Змінюємо розмір зображення до 800x1200 пропорційно
        $image->resize(800, 1200, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // Зменшити, але не збільшувати, якщо розміри менші
        });

        // Зберігаємо оброблене зображення у сховище
        $filename = uniqid() . '.png'; // Унікальна назва для файлу
        $storagePath = $path . '/' . $filename;
        Storage::disk('public')->put($storagePath, (string) $image);

        return $storagePath;
    }
}