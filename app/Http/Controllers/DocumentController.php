<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Inconsistency;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index()
    {
        return view('inconsistencis.document');
    }

    public function show($path)
    {
        // Создаем путь к файлу в хранилище
        $storagePath = storage_path('app/' . $path);
    
       
        // Получаем тип файла
        $mimeType = Storage::mimeType($storagePath);
    
        // Возвращаем файл как ответ
        return response()->file($storagePath, ['Content-Type' => $mimeType]);
    }
    public function document($inv_no)
    {
        $doc = Document::where('inv_no', $inv_no)->firstOrFail();
        $inconsistencies = $doc->inconsistencies()->with('documents')->get();

        return response()->json([
            'document' => $doc,
            'inconsistencies' => $inconsistencies,
        ]);
    }
    public function incorporationupdate(Request $request)
    {
        $inconsistency = Inconsistency::findOrFail($request->input('inconsistency_id'));      
        // confirm complected 
        $inconsistency->status =  $request->input('action');
        $inconsistency->save();
        $coment = new Comment();
        $coment->comment = $request->input('comment');
        $coment->save();
        $inconsistency->comments()->attach($coment);
        $inconsistency->save();

        return response()->json(['success' => true]);
    }
}
