<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fun;
use App\Models\Goal;

class FunController extends Controller
{
    // index 
    public function index(Request $request)
    {
        if($request->has('search')){
            $functs = Fun::where('description', 'like', "%{$request->search}%")->get();
        } 
        elseif($request->has('goal_id')){
            $functs = Fun::where('goal_id', $request->goal_id)->get();
            $goal = Goal::find($request->goal_id);
            return view('functs.index', compact('functs', 'goal'));
        }
        else {
            $functs = Fun::all();
        }
        return view('functs.index', compact('functs'));
    }
    // create
    public function create(Request $request)
    {
        $goals = Goal::all();
        $gl = $request->gl;
        return view('functs.create', compact('goals', 'gl'));
    }
    // store
    public function store(Request $request)
    {
        // validate
        $request->validate([
            'description' => 'required',
            'goal_id' => 'required'
        ]);
        // create
        Fun::create($request->all());
        // redirect
        return redirect()->route('functs.index');
    }
    // show
    public function show($id)
    {
        $funct = Fun::find($id);
        return view('functs.show', compact('funct'));
    }
    // edit
    public function edit($id)
    {
        $funct = Fun::find($id);
        $goals = Goal::all();
        return view('functs.edit', compact('funct', 'goals'));
    }
    // update
    public function update(Request $request, $id)
    {
        // validate
        $request->validate([
            'description' => 'required',
            'goal_id' => 'required'
        ]);
        // update
        $funct = Fun::find($id);
        $funct->description=$request->description;
        $funct->goal_id=$request->goal_id;
        $funct->save();
        // redirect
        return redirect()->route('functs.index');
    }
    // destroy
    public function destroy($id)
    {
        Fun::destroy($id);
        return redirect()->route('functs.index');
    }


}
