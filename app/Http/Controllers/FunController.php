<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fun;
use App\Models\Goal;
use App\Models\Position;

class FunController extends Controller
{
    // index 
    public function index(Request $request)
    {
        if($request->has('search')){
            $funs = Fun::where('description', 'like', "%{$request->search}%")->get();
        } 
        elseif($request->has('goal_id')){
            $funs = Fun::where('goal_id', $request->goal_id)->get();
            $goal = Goal::find($request->goal_id);
            return view('funs.index', compact('funs', 'goal'));
        }
        else {
            $funs = Fun::all();
        }
        return view('funs.index', compact('funs'));
    }
    // create
    public function create(Request $request)
    {
        $goals = Goal::all();
        $gl = $request->gl;
        return view('funs.create', compact('goals', 'gl'));
    }
    // store
    public function store(Request $request)
    {
        // validate
        /*$request->validate([
            'name' => 'required',
            'description' => 'required',
            'goal_id' => 'required'
        ]);*/
        // create
        $new_funct = new Fun;
        $new_funct->name=$request->name;
        $new_funct->description=$request->description;
        $new_funct->save();
        $new_funct->goals()->attach($request->goal_id);
        // redirect
        return redirect()->route('funs.index');
    }
    // show
    public function show($id)
    {
        $fun = Fun::find($id);
        return view('funs.show', compact('fun'));
    }
    // edit
    public function edit($id)
    {
        $fun = Fun::find($id);
        $goals = Goal::all();
        $positions = Position::all();
        return view('funs.edit', compact('fun', 'goals', 'positions'));
    }
    // update
    public function update(Request $request, $id)
    {
        // validate
       /* $request->validate([
            'description' => 'required',
            'goal_id' => 'required'
        ]);*/
        // update
        $funct = Fun::find($id);
        $funct->name=$request->name;
        $funct->description=$request->description;
        $funct->save();
        $funct->goals()->sync($request->goal_id);
        $funct->positions()->sync($request->position_id);
        
        return redirect()->route('funs.index');
    }
    // destroy
    public function destroy($id)
    {
        Fun::destroy($id);
        return redirect()->route('funs.index');
    }


}
