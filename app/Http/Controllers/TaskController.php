<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Models\Stage;
use App\Models\Step;
use App\Models\Dimension;
use App\Models\Control;
use App\Models\Position;
use App\Models\Personal;
use App\Models\Problem;
use App\Models\Image;
use App\Helpers\Helpers;

// File
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;



class TaskController extends Controller
{
    // index
    public function index()
    {
        $user  = Auth::user();
    //    $positions = Helpers::getSubordinatePositions($user->positions);
        $tasks = Task::where('status', '!=', 'completed')
            ->whereIn('responsible_position_id', $user
            ->profile
            ->positions ->pluck('id')
            ) 
            ->with ('project', 'stage', 'step' )    
            ->orderBy('project_id', 'desc')   
            ->get();
            $problems = Problem::where('status', '!=', 'completed') 
            ->whereIn('responsible_position_id', $user ->profile ->positions ->pluck('id')) 
            ->with ('project', 'stage', 'step' )
            ->orderBy('project_id', 'desc')
            ->get();
        return view('tasks.index', compact('tasks', 'problems'));
    }
    // show_today
    public function show_today()
    {
        $user  = Auth::user();
        $tasks = Task::where('status', '=', 'completed')
            ->where('real_end_date', '=', date('Y-m-d'))
            ->whereIn('responsible_position_id', $user->profile->positions ->pluck('id'))            
            ->with ('project', 'stage', 'step' )    
            ->orderBy('project_id', 'desc')   
            ->get();
            $problems = Problem::where('status', '=', 'completed')
            ->where('date_end', '=', date('Y-m-d'))
            ->whereIn('responsible_position_id', $user ->profile ->positions ->pluck('id'))
            ->with ('project', 'stage', 'step' )
            ->orderBy('project_id', 'desc')
            ->get();
        return view('tasks.index', compact('tasks', 'problems'));
    }
     // create
    public function create()
    {
        // 
        return view('tasks.create');
    }
    // store
    public function store(Request $request)
    {
        // 
        $task = new Task();
        $task->project_id = $request->project_id;
        $task->stage_id = $request->stage_id;
        $task->step_id = $request->step_id;
        $task->dimension_id = $request->dimension_id;
        $task->control_id = $request->control_id;
        $task->deadline_date = $request->deadline_date;
        $task->status = $request->status;
        $task->responsible_position_id = $request->responsible_position_id;
        $task->dependent_task_id = $request->dependent_task_id;
        $task->parent_task_id = $request->parent_task_id;
        $task->real_start_date = $request->real_start_date;
        $task->real_end_date = $request->real_end_date;
        $task->save();
        return redirect()->route('tasks.index');
    }

    // show
    public function show(string $id)
    {
        // 
        $task = Task::find($id);
        return view('tasks.show', compact('task'));
    }
    // edit
    public function edit(string $id)
    {
        // 
        $task = Task::find($id);
        return view('tasks.edit', compact('task'));
    }
    // update
    public function update(Request $request, string $id)
    {
        // 
        $task = Task::find($id);
       $user = Auth::user();
        $task->status = $request->status;
      //  $task->responsible_position_id =  $user->id;
        $date = date('Y-m-d');
        if($request->status == 'completed')
        {
            $task->real_end_date = $date;
        }
        else
        {
            $task->real_start_date = $date;
        }        
        $task->save();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move(public_path() . '/imagesTask/', $file->getClientOriginalName());
            $img = new Image();
            $img->name = $file->getClientOriginalName();
            $img->path = '/imagesTask/'. $file->getClientOriginalName();
            $img->extension = $file->getClientOriginalExtension();
            $img->url =  '/imagesTask/' . $file->getClientOriginalName();
            $img->alt = $file->getClientOriginalName();
            $img->title = $file->getClientOriginalName();
            $img->description = $file->getClientOriginalName();
            $img->save();
            $task->images()->attach($img->id);
        }
        return redirect()->route('tasks.index');
    }
    // destroy
    public function destroy(string $id)
    {
        // 
        $task = Task::find($id);
        $task->delete();
        return redirect()->route('tasks.index');
    }
    // Tasks clear
    public function clear()
    {
        $tasks = Task::all();
        foreach ($tasks as $task) {
            // Delete related nomenclature_task records first
            \DB::table('nomenclature_task')->where('task_id', $task->id)->delete();
            
            // Now delete the task
            $task->delete();
        }
        return redirect()->route('tasks.index');    
        
    }
    //problem
    public function problem(Request $request)
    {
        $project = Project::find($request->project_id);
        $problem = new Problem();
        $problem->name = $request->problem;
        $problem->description = $request->problem;
        $problem->priority = 1;
        $problem->date_start = now();
        $problem->date_end = $project->date_end;
        $problem->deadline = $project->date_end;
        $problem->status = $request->status;
        $problem->project_id = $request->project_id;
        $problem->stage_id = $request->stage_id;
        $problem->step_id = $request->step_id;
        $problem->task_id = $request->task_id;
        $problem->user_id = $request->user_id;
        $problem->responsible_position_id = $request->position;
        $problem->save();
        // return json response
        return response()->json(['success' => 'Problem created successfully.']);
    
       
    }
    


}
