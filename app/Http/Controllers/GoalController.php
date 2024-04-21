<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;

class GoalController extends Controller
{
    // index
    public function index()
    {
        $goals = Goal::all();
        return view('goals.index', compact('goals'));
    }
    // create
    public function create()
    {
        return view('goals.create');
    }
    // store
    public function store(Request $request)
    {
        // validate
       /* $request->validate([
            'name' => 'required',
            'description' => 'required',
            'due_date' => 'required'
        ]);*/
        // create
        $name = $request->name;
        $description = $request->description;
        $due_date = $request->due_date;
        $goal = new Goal();
        $goal->name = $name;
        $goal->description = $description;
        $goal->due_date = $due_date;
        $goal->save(); 
        // redirect
        return redirect()->route('goals.index');
    }
    // show
    public function show($id)
    {
        $goal = Goal::find($id);
        return view('goals.show', compact('goal'));
    }
    // edit
    public function edit($id)
    {
        $goal = Goal::find($id);
        return view('goals.edit', compact('goal'));
    }
    // update
    public function update(Request $request, $id)
    {
        // validate
       /* $request->validate([
            'name' => 'required',
            'description' => 'required',
            'due_date' => 'required',
            'status' => 'required'
        ]);*/
        // update
        $goal = Goal::find($id);
        $goal->name=$request->name;
        $goal->description=$request->description;
        $goal->due_date=$request->due_date;
        $goal->status=$request->status;
        // completed_on 
        if(!$request->completed_on&&$request->status==1){
            $goal->completed_date=date('Y-m-d');
        }
        elseif($request->completed_on){
            $goal->completed_date=$request->completed_on;
        }
        $goal->save();
        // redirect
        return redirect()->route('goals.index');
    }
    // destroy
    public function destroy($id)
    {
        // delete
        Goal::destroy($id);
        // redirect
        return redirect()->route('goals.index');
    }


    
}
