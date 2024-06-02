<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function show($path)
    {
        // Создаем путь к файлу в хранилище
        $storagePath = storage_path('app/' . $path);
    
       
        // Получаем тип файла
        $mimeType = Storage::mimeType($storagePath);
    
        // Возвращаем файл как ответ
        return response()->file($storagePath, ['Content-Type' => $mimeType]);
    }
}
