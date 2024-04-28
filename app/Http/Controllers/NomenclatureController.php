<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nomenclature;
use App\Helpers\FileHelpers as FileHelpers;
use App\Models\Type;

class NomenclatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $nomenclatures = Nomenclature::all();
        return view('nomenclatures.index', compact('nomenclatures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    // import
    public function import()
    {
        return view('nomenclatures.import');
    }
    // importData
    public function importData(Request $request)
    {
        $csvData = FileHelpers::csvToArray($request->file('file'));
        $type_id = $request->type_id;
       // return $csvData;
        foreach ($csvData as $dt) {
            $data = str_getcsv($dt, ";");
         //   return $data;
            $nomenclature = new Nomenclature();
            $nomenclature->name = $data[0];
            $nomenclature->article = $data[1];
            //>hasOne
            $nomenclature->type()->associate(Type::find($type_id));
            // save 
            $nomenclature->save();

        }
        return redirect()->route('nomenclaturs.index');
    }
}
