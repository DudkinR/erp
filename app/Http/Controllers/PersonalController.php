<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personal;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $personals = Personal::all();
        return view('personal.index', compact('personals'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('personal.create');

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
        $personal = new Personal([
            'abv' => $request->get('abv'),
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);
        $personal->save();
        return redirect('/personal')->with('success', 'Personal saved!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $personal = Personal::find($id);
        return view('personal.show', compact('personal'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $personal = Personal::find($id);
        return view('personal.edit', compact('personal'));

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
        $personal = Personal::find($id);
        $personal->abv = $request->get('abv');
        $personal->name = $request->get('name');
        $personal->description = $request->get('description');
        $personal->save();
        return redirect('/personal')->with('success', 'Personal updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $personal = Personal::find($id);
        $personal->delete();
        return redirect('/personal')->with('success', 'Personal deleted!');
    }
}
