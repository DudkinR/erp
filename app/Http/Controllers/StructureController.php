<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Struct;

class StructureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $structures = Struct::all();
        return view('structure.index', compact('structures'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('structure.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'abv' => 'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        $struct = new Struct([
            'abv' => $request->get('abv'),
            'name' => $request->get('name'),
            'description' => $request->get('description')
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
        return view('structure.show', compact('struct'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $struct = Struct::find($id);
        return view('structure.edit', compact('struct'));
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
}
