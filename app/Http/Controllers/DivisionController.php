<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
// position model
use App\Models\Position;


class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $divisions = Division::with('children')->get();
        return view('divisions.index', compact('divisions'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $parents = Division::all();
        $positions = Position::all();
        return view('divisions.create', compact('parents', 'positions'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // find name
        $division = Division::where('name', $request->name)->first();
        // if name exists
        if ($division) {
            return redirect()->back()->with('error', 'Division already exists');
        }
        //  division  new
        $division = new Division();
        // update division
        $division->name = $request->name;
        $division->description = $request->description;
        $division->abv = $request->abv; 
        $division->slug = $request->slug;
        $division->parent_id = $request->parent_id;
        $division->save();
        // sync positions
        $division->positions()->sync($request->positions);
        return redirect()->route('divisions.index')->with('success', 'Division created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $division = Division::find($id);
      //  return $division;
        return view('divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $parents = Division::all();
        $positions = Position::all();
        $division = Division::find($id);
        return view('divisions.edit', compact('division', 'parents', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $division = Division::find($id);
        $division->name = $request->name;
        $division->description = $request->description;
        $division->abv = $request->abv;
        $division->slug = $request->slug;
        $division->parent_id = $request->parent_id;
        $division->save();
        // sync positions
        $division->positions()->sync($request->positions);
        return redirect()->route('divisions.index')->with('success', 'Division updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
