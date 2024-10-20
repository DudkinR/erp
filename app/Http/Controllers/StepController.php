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
        // order  by id desc steps not         has controls
        $steps = Step::orderBy('id', 'desc')
          //  ->whereDoesntHave('controls')
            ->get();
        // Step::orderBy('id', 'desc')->get();
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
    public function copy_step($id)
    {
        $step = Step::find($id);
        return view('steps.create', compact('step'));
    }
  
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
        $name = $request->name;
        $step = Step::where('name', $name)->first();
        if (!$step) {
        $step = new Step();
        $step->name = $request->name;
        $step->description = $request->description;
        $step->save();
        }
        // if $request->stages is not empty || 0
        if ($request->stages_id) {
        $step->stages()->detach();
            $step->stages()->attach($request->stages_id);
        }
        // if $request->controls is not empty || 0
        if ($request->controls_id) {
            $step->controls()->detach();
            $step->controls()->attach($request->controls_id);
        }
        if($request->novisiability == '1'){
            $steps = Step::orderBy('id', 'desc')->get();
             return ['step'=>$step->id,'steps'=> $steps];
        }
        else
        return redirect()->route('steps.index');
    }
    // api_add_step
    public function api_add_step(Request $request)
    {
       // return $request->name;
        $name = $request->name;
        $step = Step::where('name', $name)->first();
        if (!$step) {
            $step = new Step();
            $step->name = $request->name;
            $step->description = $request->description;
            $step->save();
        }
        // if $request->stages is not empty || 0
        if ($request->stages_id) {
        $step->stages()->detach();
            $step->stages()->attach($request->stages_id);
        }
        // if $request->controls is not empty || 0
        if ($request->controls_id) {
            $step->controls()->detach();
            $step->controls()->attach($request->controls_id);
        }

        if($request->novisiability == '1'){
            $steps = Step::orderBy('id', 'desc')->get();
             return ['step'=>$step,'steps'=> $steps];
        }
        else
        {
            return ['step'=>$step];
        }
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
