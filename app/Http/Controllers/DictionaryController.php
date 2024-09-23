<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dictionary;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FileHelpers;

class DictionaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dictionary = Dictionary::where('editor','!=', null)
        ->orWhere('editor','!=', '')
        ->orderBy('uk', 'asc')
        ->get();
        return view('dictionary.index', compact('dictionary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //      
        return view('dictionary.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // find   word with the same uk, en, ru
        //to low
        $en = strtolower($request->en);
        $uk = strtolower($request->uk);
        $ru = strtolower($request->ru);
        $word = Dictionary::where('en', $en)->where('uk', $uk)->where('ru', $ru)->first();
        if($word){
            
            return redirect()->route('dictionary.create'
            )->with('error', 'Word already exists');
        }

        $word = new Dictionary();
        $word->uk = $uk;
        $word->en = $en; 
        $word->ru = $ru;

        $word->description = $request->description;
        $word->example = $request->example;
        $word->author = Auth::user()->tn; 
        if(Auth::user()->tn == 13344)  {
            $word->editor = Auth::user()->tn;
        } 
        $word->save();
      //  return redirect()->route('dictionary.index', with('success', 'Word added successfully'));
      return redirect('/dictionary')->with('success', 'Word added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function import(){
        return view('dictionary.import');
    }

    public function importData(Request $request)
{   
    // Проверка аутентификации пользователя
    if (Auth::check()) {
        $author = Auth::user()->tn;
    } else {
        return redirect()->back()->with('error', 'User is not authenticated');
    }

    // Проверка наличия загруженного файла
    if (!$request->hasFile('file')) {
        return redirect()->back()->with('error', 'No file uploaded');
    }

    // Определение типа файла
    $type_of_file = $request->type_of_file ?? 0;

    // Преобразование CSV в массив
    $csvData = FileHelpers::csvToArray($request->file('file'), $type_of_file);

    foreach ($csvData as $row) {
        // Поиск слова по uk
        $word = Dictionary::whereRaw('LOWER(uk) = ?', [strtolower($row[4])])->first();

        if ($word) {
            // Обновляем существующую запись
            $word->en = strtolower($row[6]);
            $word->ru = strtolower($row[5]);
            
            if (isset($row[7]) && $row[7] != null) {
                $word->description = $row[7];
            }

            $word->editor = $author;
            $word->save();
        } else {
            // Создаём новую запись
            $nword = new Dictionary();
            $nword->uk = strtolower($row[4]);
            $nword->en = strtolower($row[6]);
            $nword->ru = strtolower($row[5]);

            if (isset($row[7]) && $row[7] != null) {
                $nword->description = $row[7];  // Исправлено с $word на $nword
            }

            $nword->example = '';            
            $nword->author = $author;
            $nword->editor = $author;
            $nword->save();
        }
    }

    return redirect('/dictionary')->with('success', 'Words added successfully');
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
        $words  = Dictionary::where('editor', null)
        ->orWhere('editor', '')
        ->orderBy('uk', 'asc')
        ->limit(100)
        ->get();
        return view('dictionary.edit', compact('words'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $word = Dictionary::find($id);
        $uk = strtolower($request->uk);
        $en = strtolower($request->en);
        $ru = strtolower($request->ru);
        $word->uk = $uk;
        $word->en = $en;
        $word->ru = $ru;
        $word->description = $request->description;
        $word->example = $request->example;
        $word->editor = Auth::user()->tn;
        
        $word->save();
        return redirect('/dictionaryedit')->with('success', 'Word updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $word = Dictionary::find($id);
        if($word){
            
        $word->delete();
        }
        // edit 
        return redirect('/dictionaryedit')->with('success', 'Word deleted successfully');
    }

    
}
