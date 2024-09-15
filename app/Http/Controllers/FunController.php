<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fun;
use App\Models\Goal;
use App\Models\Position;
use App\Models\Objective;
use App\Models\Division;


class FunController extends Controller
{
    // index 
    public function index(Request $request)
    {
        if($request->has('search')){
            $funs = Fun::where('description', 'like', "%{$request->search}%")
            ->orderBy('id', 'desc')
            ->get();
        } 
        elseif($request->has('goal_id')){
            $funs = Fun::where('goal_id', $request->goal_id)->with('goals', 'objectives' )
            ->orderBy('id', 'desc')
            ->get();
            $goal = Goal::find($request->goal_id);
            return view('funs.index', compact('funs', 'goal'));
        }
        else {
            $funs = Fun::with('goals', 'objectives' )
            ->orderBy('id', 'desc')
            ->get();
        }
        $positions = Position::orderBy('name', 'asc')->get();
        $divisions = Division::orderBy('name', 'asc')->get();
        return view('funs.index', compact('funs', 'positions', 'divisions'));
    }
    // create
    public function create(Request $request)
    {
        $goals = Goal::orderBy('id', 'asc')->get();
        $gl = $request->gl;
        $objs= Objective::orderBy('id', 'asc')->get();
        $positions = Position::orderBy('id', 'asc')->get();
        return view('funs.create', compact('goals', 'gl', 'objs', 'positions'));
    }
    // store
    public function store(Request $request)
    {
        // 
        if($request->exist !== 0){
            $new_funct = Fun::find($request->exist);
        }
        else{
            $new_funct = Fun::where('name', $request->name)->where('description', $request->description)->first();
            if(!$new_funct){
                $new_funct = new Fun;
            }
            $new_funct->name=$request->name;
            $new_funct->description=$request->description;
            $new_funct->save();
        }
        if($request->goals){
            // clear old goals
            $new_funct->goals()->detach();
            $new_funct->goals()->attach($request->goals);
        }
        //objectives
        if($request->objective){
            // clear old objectives
            $new_funct->objectives()->detach();
            $new_funct->objectives()->attach($request->objective);
        }
        // positions
        if($request->positions){
            // clear old positions
          //  $new_funct->positions()->detach();
            //  order =1
            $new_funct->positions()->attach($request->positions, ['order' => 1]);
          
        }

        return redirect()->route('funs.index');
    }
    // store_api
    public function store_api(Request $request)
    {
       if($request-> fun_id){
            $fun = Fun::find($request->fun_id);
            if (isset($request->objective_id)) {
                $fun->objectives()->attach($request->objective_id);
            }
            if (isset($request->goal_id)) {
                $fun->goals()->attach($request->goal_id);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Fun successfully updated.',
                'fun' => $fun
            ], 200);
        }

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
    //store_positions_api
    public function store_positions_api(Request $request)
    {
        $fun= Fun::find($request->fun_id);
        // clear old positions
        $division_id = $request->division;
        $fun->positions()->attach($request->position , [
            'order' => 1,
            'division_id' => $division_id
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'Position successfully added to fun.',
            'fun' => $fun
        ], 200);
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
        $goals = Goal::orderBy('id', 'asc')->get();
        $positions = Position::orderBy('name', 'asc')
        ->with('divisions')
        ->get();   
        $objectives = Objective::orderBy('name', 'asc')
                ->get();
        $divisions = Division::orderBy('name', 'asc')
        ->with('positions')
        ->get();
        return view('funs.edit', compact('fun', 'goals', 'positions', 'objectives', 'divisions'));
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
        
        if($request->goals){
            // clear old goals
            $funct->goals()->detach();
            $funct->goals()->attach($request->goals);
        }
        //objectives
        if($request->objective){
            // clear old objectives
            $funct->objectives()->detach();
            $funct->objectives()->attach($request->objective);
        }
        // positions
        if($request->positions){
            // clear old positions
            $funct->positions()->detach();
            //  order =1
            $funct->positions()->attach($request->positions, ['order' => 1]);
        }

        
        return redirect()->route('funs.index');
    }
    // destroy
    public function destroy($id)
    {
        Fun::destroy($id);
        return redirect()->route('funs.index');
    }


}
