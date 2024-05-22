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



class TaskController extends Controller
{
    // index
    public function index()
    {
        // 
        $tasks = Task::where('status', '!=', 'completed')->get();
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
