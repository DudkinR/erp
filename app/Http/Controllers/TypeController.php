<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $types = Type::all();
        return view('types.index', compact('types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $fillable = ['name', 'description', 'icon', 'color', 'slug', 'parent_id'];
        $type = new Type();
        $type->name = $request->name;
        $type->description = $request->description;
        if($request->has('icon')) {
            $type->icon = $request->icon;
        }
        $type->color = $request->color;
        if($request->has('slug')) {
            $type->slug = $request->slug;
        }
        if($request->has('parent_id')) {
            $type->parent_id = $request->parent_id;
        }
        else {
            $type->parent_id = 0;
        }
         $type->save();
        return redirect()->route('types.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $type = Type::find($id);
        return view('types.show', compact('type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $type = Type::find($id);
        $types = Type::all();
        return view('types.edit', compact('type', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $type = Type::find($id);
        $type->name = $request->name;
        $type->description = $request->description;
        if($request->has('icon')) {
            $type->icon = $request->icon;
        }
        $type->color = $request->color;
        if($request->has('slug')) {
            $type->slug = $request->slug;
        }
        if($request->has('parent_id')) {
            $type->parent_id = $request->parent_id;
        }
        else {
            $type->parent_id = 0;
        }
        $type->save();
        return redirect()->route('types.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $type = Type::find($id);
        $type->delete();
        return redirect()->route('types.index');
    }
    // import data from csv file
    public function import()
    {
        return view('types.import');
    }
    // import data from csv file
    public function importData(Request $request)
    {
        if($request->has('parent_id')) {
            $parent_id = $request->parent_id;
            // delete all records with parent_id=$parent_id
            Type::where('parent_id', $parent_id)->delete();
            $csvData = FileHelpers::csvToArray($request->file('file'));
            foreach ($csvData as $data) {
                $type = new Type();
                $type->name = $data['name'];
                $type->description = $data['description'];
                if(isset($data['icon'])) {
                    $type->icon = $data['icon'];
                }
                $type->color = $data['color'];
                if(isset($data['slug'])) {
                    $type->slug = $data['slug'];
                }
                $type->parent_id = $parent_id;
                $type->save();
            }
        }
       
    }
}
