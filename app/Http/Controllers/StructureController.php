<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\StringHelpers as StringHelpers;
use App\Helpers\FileHelpers as FileHelpers;
use App\Models\Struct;
use App\Models\Position;
use App\Models\Division;


class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $structuries = Struct::orderBy('id', 'desc')->get();
        return view('structures.index', compact('structuries'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $positions = Position::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        return view('structures.create', compact('positions', 'divisions', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
   /* protected $fillable = ['abv','name', 'description','parent_id','kod','status'];
        */
        $struct = new Struct([
            'abv' => $request->slug,
            'name' => $request->name,
            'description' => $request->description,
            'status' =>  $request->status,
            'kod' => $request->kod,
            'parent_id' => $request->parent_id

        ]);
        $struct->save();
        // find position if not exist create new    protected $fillable = ['name', 'description', 'start', 'data_start', 'closed', 'data_closed'];
        $position = Position::where('name', $request->position)->first();
        if(!$position){
            $position = new Position();
            $position->name = $request->name;
            $position->description = $request->description;
            $position->start = $request->status;
            $position->data_start = date('Y-m-d');
            $position->save();
        }
        // Добавлять только уникальные значения
        $struct->positions()-> syncWithoutDetaching($position->id);
        // if has division
        if($request->division_id){
            $struct->divisions()->attach($request->division_id);
        }

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
        $structure = Struct::find($id);
        $parents = Struct::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        return view('structures.edit', compact('structure', 'parents', 'positions', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $struct = Struct::find($id);
        $struct->abv = $request->abv;
        $struct->name = $request->name;
        $struct->description = $request->description;
        $struct->status = $request->status;
        $struct->kod = $request->kod;
        $struct->parent_id = $request->parent_id;
        $struct->save();
        //  position
        $position = Position::where('name', $request->position)->first();
        if(!$position){
            $position = new Position();
            $position->name = $request->name;
            $position->description = $request->description;
            $position->start = $request->status;
            $position->data_start = date('Y-m-d');
            $position->save();
        }
        // Добавлять только уникальные значения
        $struct->positions()-> syncWithoutDetaching($position->id);
        // if has division
        if($request->division_id){
            $struct->divisions()->attach($request->division_id);
        }
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
        if($request->type_of_file)
        $type_of_file =$request->type_of_file;
        else
        $type_of_file = 0;
     //   return $type_of_file;
        $csvData = FileHelpers::csvToArray($request->file('file'),$type_of_file);
      //  return $csvData;
            foreach ($csvData as $line) {
            $data = str_getcsv($line, ";"); // разбивка строки на столбцы
           
                if (preg_match('/\d{2}-\d{6}/', $data[1])) {
                    // find exist struct
                    $struct = Struct::where('kod', $data[1])->first();
                    if(!$struct){
                        $struct = new Struct();
                        $struct->abv=StringHelpers::abv($data[0]);
                        $struct->name=$data[0];
                        $struct->status='active';
                        $struct->kod=$data[1];
                        $struct->description=$data[0]; 
                    }
                    $struct->parent_id=$this->parent_id($data[2]);
                    $struct->save();
              }
        }
        $structures = Struct::orderBy('id', 'desc')->get();
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
