<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fun;
use App\Models\Goal;
use App\Models\Position;
use App\Models\Objective;


class FunController extends Controller
{
    // index 
    public function index(Request $request)
    {
        if($request->has('search')){
            $funs = Fun::where('description', 'like', "%{$request->search}%")->get();
        } 
        elseif($request->has('goal_id')){
            $funs = Fun::where('goal_id', $request->goal_id)->with('goals', 'objectives' )->get();
            $goal = Goal::find($request->goal_id);
            return view('funs.index', compact('funs', 'goal'));
        }
        else {
            $funs = Fun::with('goals', 'objectives' )->get();
        }
        $positions = Position::all();
        return view('funs.index', compact('funs', 'positions'));
    }
    // create
    public function create(Request $request)
    {
        $goals = Goal::all();
        $gl = $request->gl;
        $objs= Objective::all();
        return view('funs.create', compact('goals', 'gl', 'objs'));
    }
    // store
    public function store(Request $request)
    {

        $new_funct = new Fun;
        $new_funct->name=$request->name;
        $new_funct->description=$request->description;
        $new_funct->save();
        $new_funct->goals()->attach($request->goal_id);
        if($request->gl){
            $new_funct->goals()->attach($request->gl);
        }

        // redirect
        return redirect()->route('funs.index');
    }
    // store_api
    public function store_api(Request $request)
    {
    

        $errors = [];
    
        if (!$request->name) {
            $errors[] = 'name is required';
          
        } else {
 
            $fun = Fun::where('name', $request->name)->first();
            if ($fun) {
                
                if (isset($request->objective_id)) {
                   $fun->objectives()->attach($request->objective_id);
                }
                if (isset($request->goal_id)) {
                  $fun->goals()->attach($request->goal_id);
                }
               // return $fun;
                $errors[] = 'name already exists'; 
                return response()->json([
                    'status' => 'success',
                    'errors' => $errors,
                    'message' => 'The given data was invalid.',
                    'fun' => $fun
                ], 200);
            } else {
                $new_funct = new Fun;
                $new_funct->name = $request->name;     
                $new_funct->description = $request->description;
               
                $new_funct->save();    
                if (isset($request->objective_id)) {
                    $new_funct->objectives()->attach($request->objective_id);
                }
                if (isset($request->goal_id)) {
                  $new_funct->goals()->attach($request->goal_id);
                }
                return response()->json([
                    'status' => 'success',
                    'errors' => $errors,
                    'message' => 'Fun successfully created.',
                    'fun' => $new_funct
                ], 200);
            }
        }
        return response()->json([
            'status' => 'error',
            'errors' => $errors,
            'message' => 'The given data was invalid.',
        ], 422);
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
