<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stage;
use App\Models\Task;
use App\Models\Project;
use App\Models\Step;
use App\Models\Dimension;

class StageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // order desc where description empty
        $stages =  Stage::orderBy('id', 'desc')->get();
        return view('stages.index', compact('stages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $mass=[];
        if(isset($request->project)){
            $mass[]=['project'=>$request->project];
        }
        if(isset($request->stage)){
            $mass[]=['stage'=>$request->stage];
        }
        
        return view('stages.create', compact('mass'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //        
        $stage = new Stage();
        $stage->name = $request->name;
        $stage->description = $request->description;
        $stage->save();
        if(isset($request->add_par)){
            foreach($request->add_par as $key=>$value){
             if($key=='project'){
                 $stage->projects()->attach($value);
             }
             if($key=='stage'){
                    $stage->stages()->attach($value);
              }
            }
            
        }
        return redirect()->route('stages.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $stage = Stage::find($id);
        return view('stages.show', compact('stage'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $stage = Stage::find($id);
        return view('stages.edit', compact('stage'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $stage = Stage::find($id);
        $stage->name = $request->name;
        $stage->description = $request->description;
        $stage->save();
        if(isset($request->steps_id)){
            // clear old steps
            $stage->steps()->detach();
            $stage->steps()->sync($request->steps_id);
        }
        
        return redirect()->route('stages.show', $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $stage = Stage::find($id);
        $stage->delete();
        return redirect()->route('stages.index');
    }


    //remove_step
    public function remove_step(Request $request)
    {
        $stage = Stage::find($request->stage_id);
        $stage->steps()->detach($request->step_id);
        return response()->json((object)['status' => 'success']);
    }
    // new_steps
    public function new_steps(Request $request)
    {
       // return $request->all()->keyBy('id')->values();{"_token":"lfRKYIfJDNioSd8SiE2D6m9K6sTtq6TZReRsFZ5E","_method":"POST","stage_id":"12","project_id":"204","deadline":"2024-05-24","steps":[{"order":"1","position_id":"1","count":"1","type":"photo","checkpoints":"on","step_id":"12"}
       // нужно сгенерировать задания и сохранить Task
       // fillable fields `id`, `project_id`, `stage_id`, `step_id`, `dimension_id`, `control_id`, `deadline_date`, `status`, `responsible_position_id`, `dependent_task_id`, `parent_task_id`, `real_start_date`, `real_end_date`, `created_at`, `updated_at` , 'count'
       $project_id = $request->project_id;
       $stage_id = $request->stage_id; 
       $deadline = $request->deadline;
       $start_date = $request->start_date;
      // $count = $request->count;
      // $type = $request->type;
       $dimension = Dimension::where('name', 'штук')->first();
       

       if (!$dimension) {
           $dimension = new Dimension();
           $dimension->name = 'штук';
           $dimension->description = 'штук';
           $dimension->save();
       }
       
       $dimension_id = $dimension->id;
       
       $steps = $request->steps;
       if($steps){
       foreach ($steps as $step) {
        if (isset($step['checkpoints']) && $step['checkpoints'] == 'on') {
            $task = new Task();
            $task->project_id = $project_id;
            $task->stage_id = $stage_id;
            $task->step_id = $step['step_id'];
            $task->dimension_id = $dimension_id;
            $task->deadline_date = $deadline;
            $task->real_start_date = $start_date;
            $task->status = 'new';
            $task->responsible_position_id = $step['position_id'];
            $task->count = $step['count'];
            $task->type = $step['type'];
            $task->order = $step['order'];
            $task->save();
            }
        }
    }
        // redirect to show project_id
        return redirect()->route('projects.show', $project_id);

    }
}
