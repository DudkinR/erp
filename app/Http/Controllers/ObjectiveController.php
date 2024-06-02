<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objective; 
use App\Models\Goal;
use App\Models\Fun;

class ObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $objectives = Objective::all();
        return view('objectives.index', compact('objectives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $goals = Goal::all();
        $functs = Fun::all();
        return view('objectives.create', compact('goals', 'functs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $objective = new Objective();
        $objective->name = $request->name;
        $objective->description = $request->description;
        $objective->save();
        if ($request->goals) {
            $objective->goals()->attach($request->goals);
        }
        if ($request->functs) {
            $objective->functs()->attach($request->functs);
        }
        return redirect()->route('objectives.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $objective = Objective::find($id)->with('goals', 'functs')->first();
        return view('objectives.show', compact('objective'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $objective = Objective::find($id);
        $goals = Goal::all();
        $functs = Fun::all();
        return view('objectives.edit', compact('objective', 'goals', 'functs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $objective = Objective::find($id);
        $objective->name = $request->name;
        $objective->description = $request->description;
        $objective->save();
        if ($request->functs) {
            $objective->functs()->sync($request->functs);
        }
        if ($request->goals) {
            $objective->goals()->sync($request->goals);
        }
        return redirect()->route('objectives.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $objective = Objective::find($id);
        // clear goals and functs
        $objective->goals()->detach();
        $objective->functs()->detach();
        $objective->delete();
        return redirect()->route('objectives.index');
    }
}
