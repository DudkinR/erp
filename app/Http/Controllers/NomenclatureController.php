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
       // return $nomenclatures;
        return view('nomenclatures.index', compact('nomenclatures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('nomenclatures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $nomenclature = new Nomenclature();
        $nomenclature->name = $request->name;
        $nomenclature->article = $request->article;
        $nomenclature->description = $request->description;
        $nomenclature->image = $request->image;
        //>hasOne
        $nomenclature->type()->associate(Type::find($request->type_id));
        // save
        $nomenclature->save();
        return redirect()->route('nomenclaturs.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.show', compact('nomenclature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.edit', compact('nomenclature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        $nomenclature->name = $request->name;
        $nomenclature->article = $request->article;
        $nomenclature->description = $request->description;
        $nomenclature->image = $request->image;
        //>hasOne
        $nomenclature->type()->associate(Type::find($request->type_id));
        // save
        $nomenclature->save();
        return redirect()->route('nomenclaturs.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        $nomenclature->delete();
        return redirect()->route('nomenclaturs.index');

    }
    // createdoc
    public function createDoc(string $id)
    {
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.createDoc', compact('nomenclature'));
    }
    // nomenclatures.img.create
    public function createImg(string $id)
    {
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.createImg', compact('nomenclature'));
    }
    // nomenclatures.img.store
    public function storeImg(Request $request)
    {
        $id = $request->nomenclature_id;
        $nomenclature = Nomenclature::find($id);
        
        // rename file and download asset('storage/nomenclature/'.$nomenclature->image
        $file = $request->file('image');
        $filename = $nomenclature->id . '.' . $file->getClientOriginalExtension();
        // delete old file any getClientOriginalExtension
        if($nomenclature->image)
        {
            $oldfile = public_path('storage/nomenclature/'.$nomenclature->image);
            if(file_exists($oldfile))
            {
                unlink($oldfile);
            }
        }

        $file->move(public_path('storage/nomenclature'), $filename);
        $nomenclature->image = $filename;

        $nomenclature->save();
        return redirect()->route('nomenclaturs.show', $id);
    }
    // import
    public function import()
    {
        return view('nomenclatures.import');
    }
    // importData
    public function importData(Request $request)
    {
        if($request->type_of_file)
            $type_of_file =$request->type_of_file;
            else
            $type_of_file = 0;
            $csvData = FileHelpers::csvToArray($request->file('file'),$type_of_file);
        if($request->type_id)
            $type_id =$request->type_id;
            else
            $type_id = 0;    
       
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
