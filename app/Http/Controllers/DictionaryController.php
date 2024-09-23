<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dictionary;
use Illuminate\Support\Facades\Auth;

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
    public function import()
    {
        $word_cards = $this->cards;
        foreach ($word_cards as $card) {
            if($card['uk'] == '' ){
                continue;
            }
            if(count(explode(' ', $card['uk'])) > 2){
                continue;
            }
            $word = new Dictionary();
            $word->uk =strtolower ($card['uk']);
            $word->en =strtolower ($card['en']);
            $word->ru = strtolower($card['ru']);
            $word->description = '';
            $word->example = '';
            $word->author = Auth::user()->tn;
            $word->save();
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
