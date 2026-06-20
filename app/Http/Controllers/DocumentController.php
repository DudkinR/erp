<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Inconsistency;
use App\Models\Comment;
use Illuminate\Support\Facades\Storage;
use App\Models\Kndk;
use Illuminate\Support\Facades\DB;

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
        $doc = Document::where('inv_no', $inv_no)
        ->with('kndks')
        ->firstOrFail();
        $inconsistencies = $doc->inconsistencies()->with('documents')->get();

        return response()->json([
            'document' => $doc,
            'inconsistencies' => $inconsistencies,
        ]);
    }
        public function document_show($inv_no)
    {
        $doc = Document::where('inv_no', $inv_no)
        ->with('kndks')
        ->firstOrFail();
        $inconsistencies = $doc->inconsistencies()->with('documents')->get();

       return view('kndks.document', [
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
    public function create()
    {
        // Отримуємо всі КНДК разом із жадібним завантаженням (Eager Loading) зв'язків
        $kndks = Kndk::with(['responsibles', 'positions', 'divisions'])
            ->withCount('documents') // додаємо підрахунок документів, якщо це потрібно для форми
            ->orderBy('id')
            ->get();        
            
        return view('documents.create', compact('kndks'));
    }
 

    /**
     * Збереження нового документа та його зв'язків з КНДК
     */
    public function store(Request $request)
    {
        // 1. Валідація вхідних даних (всі fillable поля + масив КНДК)
        $validated = $request->validate([
            'inv_no'             => 'required|string|max:100|unique:documents,inv_no', // замініть 'documents' на вашу таблицю
            'doc_type'           => 'nullable|string|max:255',
            'code'               => 'nullable|string|max:100',
            'organization'       => 'nullable|string|max:255',
            'short_content'      => 'nullable|string',
            'date_reg'           => 'nullable|date',
            'date_start'         => 'nullable|date',
            'date_end'           => 'nullable|date',
            'distribution'       => 'nullable|string|max:255',
            'replaced_content'   => 'nullable|string|max:255',
            'replaced_by'        => 'nullable|string|max:255',
            'change_no'          => 'nullable|string|max:50',
            'page_count'         => 'nullable|integer|min:1',
            'note'               => 'nullable|string',
            'storage_location'   => 'nullable|string|max:255',
            'registration_date'  => 'nullable|date',
            'is_cancelled'       => 'nullable|boolean',
            'cancellation_date'  => 'nullable|required_if:is_cancelled,1|date',
            'is_reissued'        => 'nullable|boolean',
            'author'             => 'nullable|string|max:255',
            'approved_by'        => 'nullable|string|max:255',
            'project'            => 'nullable|string|max:255',
            
            // Оскільки у формі поле kndk_ids має атрибут 'required', валідуємо його як обов'язковий масив
            'kndk_ids'           => 'required|array|min:1',
            'kndk_ids.*'         => 'exists:kndk,id', // замініть 'kndk' на назву вашої таблиці КНДК
        ]);

        // 2. Безпечна обробка логічних прапорців (checkbox)
        $validated['is_cancelled'] = $request->has('is_cancelled') ? 1 : 0;
        $validated['is_reissued']  = $request->has('is_reissued') ? 1 : 0;

        // Якщо документ не скасовано, примусово очищаємо дату скасування
        if (!$validated['is_cancelled']) {
            $validated['cancellation_date'] = null;
        }

        // 3. Збереження через модель із використанням транзакції (щоб не розірвати зв'язки при збої)
        return DB::transaction(function () use ($validated) {
            
            // Створення запису в базі через масове заповнення моделі (Fillable)
            $document = Document::create($validated);

            // Прив'язка ID КНДК до створеного документа через зв'язок Many-to-Many
            $document->kndks()->sync($validated['kndk_ids']);

            // Повернення на список із зеленим сповіщенням успіху
            return redirect()
                ->route('documents.index')
                ->with('success', "Документ № {$document->inv_no} успішно створено та пов'язано з КНДК.");
        });
    }

}
