<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Project;
use App\Models\Image;

class ProblemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //project_id
        if($request->project_id){
            $project= Project::find($request->project_id);
            $problems = $project->problems;
          
        }
        else{

        $problems = Problem::where('status','!=', 'closed')->get();
        }
        return view('problems.index', compact('problems'));

    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $project_id = null;
        $stage_id = null;
        $step_id = null;
        $control_id = null;
        $personal_id = null;
        if($request->project_id){
            $project_id = $request->project_id;
        }
        if($request->stage_id){
            $stage_id = $request->stage_id;
        }
        if($request->step_id){
            $step_id = $request->step_id;
        }
        if($request->control_id){
            $control_id = $request->control_id;
        }
        if($request->personal_id){
            $personal_id = $request->personal_id;
        }
        return view('problems.create', compact('project_id', 'stage_id', 'step_id', 'control_id', 'personal_id'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $problem = new Problem();
        $problem->name = $request->name;
        if($request->description){
            $problem->description = $request->description;
        }
        if($request->priority){
            $problem->priority = $request->priority;
        }
        if($request->date_start){
            $problem->date_start = $request->date_start;
        }
        if($request->date_end){
            $problem->date_end = $request->date_end;
        }
        if($request->deadline){
            $problem->deadline = $request->deadline;
        }
        if($request->status){
            $problem->status = $request->status;
        }
        if($request->project_id){
            $problem->project_id = $request->project_id;
        }
        if($request->stage_id){
            $problem->stage_id = $request->stage_id;
        }
        if($request->step_id){
            $problem->step_id = $request->step_id;
        }
        if($request->control_id){
            $problem->control_id = $request->control_id;
        }
        if($request->personal_id&& $request->personal_id!==0){
            $problem->personals()->attach($request->personal_id);
        }
        $problem->save();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move(public_path() . '/ProblemImages/', $file->getClientOriginalName());
            $img = new Image();
            $img->name = $file->getClientOriginalName();
            $img->path = public_path() . '/ProblemImages/' . $file->getClientOriginalName();
            $img->extension = $file->getClientOriginalExtension();
            $img->size = $file->getSize();
            $img->mime_type = $file->getMimeType();
            $img->url = '/ProblemImages/' . $file->getClientOriginalName();
            $img->save();
             // add img to problem
            if($img){
                $problem->images()->attach($img->id);
            }
        }
        return redirect()->route('problems.show', $problem->id)->with('success', 'Problem created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $problem = Problem::find($id);
         $personals = \App\Models\Personal::where('status',  'Робота')->get();
        return view('problems.show', compact('problem', 'personals'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $problem = Problem::find($id);
        $personals = \App\Models\Personal::where('status',  'Робота')->get();
        return view('problems.edit', compact('problem', 'personals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $problem = Problem::find($id);
        if($request->name){
            $problem->name = $request->name;
        }
        if($request->description){
            $problem->description = $request->description;
        }
        if($request->priority){
            $problem->priority = $request->priority;
        }
        if($request->date_start){
            $problem->date_start = $request->date_start;
        }
        if($request->date_end){
            $problem->date_end = $request->date_end;
        }
        if($request->deadline){
            $problem->deadline = $request->deadline;
        }
        if($request->status){
            $problem->status = $request->status;
        }
        if($request->project_id){
            $problem->project_id = $request->project_id;
        }
        if($request->stage_id){
            $problem->stage_id = $request->stage_id;
        }
        if($request->step_id){
            $problem->step_id = $request->step_id;
        }
        if($request->control_id){
            $problem->control_id = $request->control_id;
        }
        if($request->personal){
            foreach($request->personal as $personal){
                if($problem->personals->contains($personal)){ 
                    // add info view and comment
                    $problem->personals()->updateExistingPivot($personal,
                     ['view' => $request->view, 'comment' => $request->comment]);
                }
                else{
                    $problem->personals()->attach($personal,
                     ['view' => $request->view, 'comment' => $request->comment]);
                }
            }
        

        }
        $problem->save();
        return redirect()->route('problems.show', $problem->id)->with('success', 'Problem updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $problem = Problem::find($id);
        $problem->delete();
        return redirect()->route('problems.index')->with('success', 'Problem deleted successfully.');
        
    }
}
