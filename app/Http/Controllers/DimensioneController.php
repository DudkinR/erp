<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dimension;
use App\Helpers\FileHelpers as FileHelpers;

class DimensioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dimensions = Dimension::all();
        return view('dimensions.index', compact('dimensions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('dimensions.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //`abv`, `name`, `kod`, `description`, `formula`, `unit`, `type`, `value`, `min_value`, `max_value`, `step`, `default_value`, `default_min_value`, `default_max_value`, `default_step`, `default_type`, `default_unit`
        $dimension = new Dimension();
        $dimension->name = $request->name;
        if($request->kod){
            $dimension->kod = $request->kod;
        }
        if($request->formula){
            $dimension->formula = $request->formula;
        }
        if($request->unit){
            $dimension->unit = $request->unit;
        }
        if($request->type){
            $dimension->type = $request->type;
        }
        if($request->value){
            $dimension->value = $request->value;
        }
        if($request->min_value){
            $dimension->min_value = $request->min_value;
        }
        if($request->max_value){
            $dimension->max_value = $request->max_value;
        }
        if($request->step){
            $dimension->step = $request->step;
        }
        if($request->default_value){
            $dimension->default_value = $request->default_value;
        }
        if($request->default_min_value){
            $dimension->default_min_value = $request->default_min_value;
        }
        if($request->default_max_value){
            $dimension->default_max_value = $request->default_max_value;
        }
        if($request->default_step){
            $dimension->default_step = $request->default_step;
        }
        if($request->default_type){
            $dimension->default_type = $request->default_type;
        }
        if($request->default_unit){
            $dimension->default_unit = $request->default_unit;
        }
        if($request->description){
            $dimension->description = $request->description;
        }
        $dimension->save();
        return redirect()->route('dimensions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $dimension = Dimension::find($id);
        return view('dimensions.show', compact('dimension'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $dimension = Dimension::find($id);
        return view('dimensions.edit', compact('dimension'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $dimension = Dimension::find($id);
        $dimension->name = $request->name;
        if($request->kod){
            $dimension->kod = $request->kod;
        }
        if($request->description){
        $dimension->description = $request->description;
        }
        if($request->formula){
            $dimension->formula = $request->formula;
        }
        if($request->unit){
            $dimension->unit = $request->unit;
        }
        if($request->type){
            $dimension->type = $request->type;
        }
        if($request->value){
            $dimension->value = $request->value;
        }
        if($request->min_value){
            $dimension->min_value = $request->min_value;
        }
        if($request->max_value){
            $dimension->max_value = $request->max_value;
        }
        if($request->step){
            $dimension->step = $request->step;
        }
        if($request->default_value){
            $dimension->default_value = $request->default_value;
        }
        if($request->default_min_value){
            $dimension->default_min_value = $request->default_min_value;
        }
        if($request->default_max_value){
            $dimension->default_max_value = $request->default_max_value;
        }
        if($request->default_step){
            $dimension->default_step = $request->default_step;
        }
        if($request->default_type){
            $dimension->default_type = $request->default_type;
        }
        if($request->default_unit){
            $dimension->default_unit = $request->default_unit;
        }
        $dimension->save();
        // show
        return redirect()->route('dimensions.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $dimension = Dimension::find($id);
        $dimension->delete();
        return redirect()->route('dimensions.index');
    }

    // import
    public function import()
    {
        return view('dimensions.import');
    }

    // importdata
    public function importdata(Request $request)
    {
        if($request->type_of_file)
            $type_of_file =$request->type_of_file;
            else
            $type_of_file = 0;
            $csvData = FileHelpers::csvToArray($request->file('file'),$type_of_file);
        foreach ($csvData as $dt) {
            $data = str_getcsv($dt, ";");
            if(Dimension::where('name', $data[0])->exists()){
                continue;
            }
            $dimension = new Dimension();
            $dimension->name = $data[0];
            $dimension->kod = $data[1];
            $dimension->description = $data[0];
            $dimension->save();
        }
        return redirect()->route('dimensions.index');
    }

}
