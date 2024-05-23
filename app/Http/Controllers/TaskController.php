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
        //Auth::user();
        $pers = Personal::where('fio', Auth::user()->name)->first();
        $positions = Helpers::getSubordinatePositions($pers->positions);
        $tasks = Task::where('status', '!=', 'completed')
            ->whereIn('responsible_position_id', $positions) 
            ->with ('project', 'stage', 'step' )    
            ->orderBy('project_id', 'desc')   
            ->get();
        return view('tasks.index', compact('tasks'));
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
        $task->responsible_position_id =  $user->id;
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
        if(isset($request->image))
        {
            //  protected $fillable = ['name', 'path', 'extension', 'size', 'mime_type', 'url', 'alt', 'title', 'description'];
            // onload file 
            $file = $request->file('image');
            $name = $file->getClientOriginalName();
            $path = $file->store('images/tasks');
            $image = new Image();
            $image->name = $request->image->getClientOriginalName();
            $image->path = $request->image->store('images');
            $image->extension = $request->image->extension();
            $image->size = $request->image->getSize();
            $image->mime_type = $request->image->getMimeType();
            $image->url = Storage::url($path);


                    $task->images()->attach($image->id);
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
    


}
