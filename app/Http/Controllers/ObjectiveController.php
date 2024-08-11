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
        //orderBy('id', 'desc')
        $objectives =Objective::with('goals', 'functs')
            ->orderBy('id', 'desc')
            ->get(); 
        
        return view('objectives.index', compact('objectives'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $goals = Goal::orderBy('id', 'desc')->get();
        $functs = Fun::orderBy('id', 'desc')->get();
        $objectives = Objective::orderBy('id', 'desc')->get();
        return view('objectives.create', compact('goals', 'functs', 'objectives'));
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
        //parent_id!==''
        if($request->parent_id&&$request->parent_id!==''){
            // find order objective
            $next_order = Objective::find($request->parent_id)->children()->count();
            $objective->children()->attach($request->parent_id, ['order' => $next_order]);
        }
        return redirect()->route('objectives.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $objective = Objective::find($id);
        $parent = $objective->parent()->get();
        //return $parent;
        return view('objectives.show', compact('objective', 'parent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $objective = Objective::find($id);
        $goals = Goal::orderBy('id', 'desc')->get();
        $functs = Fun::orderBy('id', 'desc')->get();
        $objectives = $this->parent_up($id);
        $chlildren =$objective->children()->orderBy('order', 'asc')->get();
        $parent = $objective->parent()->first();
        return view('objectives.edit', compact('objective', 'goals', 'functs',  'chlildren', 'objectives', 'parent'));

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
        //parent_id!==''
        if($request->parent_id!==''){
            // find order objective
            $next_order = Objective::find($request->parent_id)->children()->count();
            // clear other parent 
            $objective->parent()->detach();
           $objective->parent()->attach($request->parent_id, ['order' => $next_order]);
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

    public function parent_up($id){
        // найти все объекты parent_id и все обекты выше их        
        return   Objective::find($id)->parent()->first()->children()->orderBy('order', 'asc')->get();      
       
    }
}
