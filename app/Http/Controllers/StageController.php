<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stage;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // order desc where description empty
        $stages =  Stage::orderBy('id', 'desc')->get();
        return view('stages.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $mass=[];
        if(isset($request->project)){
            $mass[]=['project'=>$request->project];
        }
        if(isset($request->stage)){
            $mass[]=['stage'=>$request->stage];
        }
        
        return view('stages.create', compact('mass'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //        
        $stage = new Stage();
        $stage->name = $request->name;
        $stage->description = $request->description;
        $stage->save();
        if(isset($request->add_par)){
            foreach($request->add_par as $key=>$value){
             if($key=='project'){
                 $stage->projects()->attach($value);
             }
             if($key=='stage'){
                    $stage->stages()->attach($value);
              }
            }
            
        }
        return redirect()->route('stages.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $stage = Stage::find($id);
        return view('stages.show', compact('stage'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $stage = Stage::find($id);
        return view('stages.edit', compact('stage'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $stage = Stage::find($id);
        $stage->name = $request->name;
        $stage->description = $request->description;
        $stage->save();
        if(isset($request->steps_id)){
            // clear old steps
            $stage->steps()->detach();
            $stage->steps()->sync($request->steps_id);
        }
        
        return redirect()->route('stages.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $stage = Stage::find($id);
        $stage->delete();
        return redirect()->route('stages.index');
    }


    //remove_step
    public function remove_step(Request $request)
    {
        $stage = Stage::find($request->stage_id);
        $stage->steps()->detach($request->step_id);
        return response()->json((object)['status' => 'success']);
    }
}
