<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\StringHelpers as StringHelpers;
use App\Helpers\FileHelpers as FileHelpers;
use App\Models\Struct;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $structuries = Struct::all();
        return view('structures.index', compact('structuries'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('structures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
   /*     $request->validate([
            'abv' => 'required',
            'name' => 'required',
            'description' => 'required',

        ]);
        */
        $struct = new Struct([
            'abv' => $request->get('abv'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'status' => 'active',
            'kod' => '0',
            'parent_id' => '0'

        ]);
        $struct->save();
        return redirect('/structure')->with('success', 'Structure saved!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $struct = Struct::find($id);
        return view('structures.show', compact('struct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $struct = Struct::find($id);
        return view('structures.edit', compact('struct'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'abv' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        $struct = Struct::find($id);
        $struct->abv = $request->get('abv');
        $struct->name = $request->get('name');
        $struct->description = $request->get('description');
        $struct->save();
        return redirect('/structure')->with('success', 'Structure updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $struct = Struct::find($id);
        $struct->delete();
        return redirect('/structure')->with('success', 'Structure deleted!');
        
    }

    //import csv file
    public function import()
    {
        return view('structures.import');
    }
    // import data from csv file
    public function importData(Request $request)
    {
        // clear table
        // Struct::truncate();
       $csvData = FileHelpers::csvToArray($request->file('file'));
      // return $csvData;
        foreach ($csvData as $line) {
            $data = str_getcsv($line, ";"); // разбивка строки на столбцы
            // if $data[1] has format 00-000015
                if (preg_match('/\d{2}-\d{6}/', $data[1])) {
                    $struct = new Struct();
                    $struct->abv=StringHelpers::abv($data[0]);
                    $struct->name=$data[0];
                    $struct->description=$data[0];
                    $struct->status='active';
                    $struct->kod=$data[1];
                    $struct->parent_id=$this->parent_id($data[2]);
                    
                    $struct->save();
              }
        }
        $structures = Struct::all();
       // return $structures;
        return redirect('/structure')->with('success', 'Data imported!');
        
    }

    
    public function parent_id($kod){
        $struct = \App\Models\Struct::where('kod', $kod)->first();
        if($struct)
            return $struct->id;
        else
        return 0;
    }
}
