<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Step;
use App\Models\Stage;
use App\Models\Control;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $steps = Step::all();
        return view('steps.index', compact('steps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('steps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $step = new Step();
        $step->name = $request->name;
        $step->description = $request->description;
        $step->save();
        // if $request->stages is not empty || 0
        if ($request->stages_id) {
            $step->stages()->attach($request->stages_id);
        }
        // if $request->controls is not empty || 0
        if ($request->controls_id) {
            $step->controls()->attach($request->controls_id);
        }
        return redirect()->route('steps.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $step = Step::find($id);
        return view('steps.show', compact('step'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $step = Step::find($id);
        return view('steps.edit', compact('step'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $step = Step::find($id);
        $step->name = $request->name;
        $step->description = $request->description;
        $step->save();
        // stages 
        if ($request->stages_id) {
            $step->stages()->sync($request->stages_id);
        }
        // controls
        if ($request->controls_id) {
            $step->controls()->sync($request->controls_id);
        }
        return redirect()->route('steps.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $step = Step::find($id);
        $step->delete();
        return redirect()->route('steps.index');
    }
}
