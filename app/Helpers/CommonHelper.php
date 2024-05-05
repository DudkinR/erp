<?php

// Path: app/Helpers/CommonHelper.php
namespace App\Helpers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Stage;
use App\Models\Step;
use App\Models\Control;
use App\Models\Dimension;
use App\Models\Position;

use DateTime;


class CommonHelper extends Helpers
{
   // 
   public static function formattedDate($date)
   {
       if (empty($date)) {
           return NULL;
       }
       
       $dateTime = DateTime::createFromFormat('d.m.Y', $date);
       
       // Проверяем, удалось ли преобразовать строку в дату
       if ($dateTime !== false) {
           // Если удалось, возвращаем дату в формате Y-m-d для записи в базу данных
           return $dateTime->format('Y-m-d');
       } else {
           // Если не удалось преобразовать строку в дату, возвращаем NULL
           return NULL;
       }
   }
   // add new stages
   public function addNewStages(Request $request)
   {
    $mass=[];
    $project_id = $request->project_id;
       $stage_id = $request->stage_id;
       $deadline = $request->deadline;
       $responsible_position_id_default = $request->responsible_position_id;
       $stage = Stage::find($stage_id);
       foreach($stage->steps as $step){
           foreach($step->controls as $control){
               foreach($control->dimensions as $dimension){
                   $task = new Task();

                   $task->project_id = $project_id;
                   $task->stage_id = $stage_id;
                   $task->step_id = $step->id;
                   $task->dimension_id = $dimension->id;
                   $task->control_id = $control->id;
                   $task->deadline_date = $deadline;
                   if($dimension->parent_id == 0)
                       $task->status = 'active';
                   else{
                       $task->status = 'pending';
                       // parent_task_id
                       $task->parent_task_id = $this->findParentTask($stage_id, $step->id, $dimension->parent_id, $control->id)->id; 
                   }
                   if($task->position_id == null)
                       $task->responsible_position_id = $responsible_position_id_default;
                   else
                       $task->responsible_position_id = $task->position_id;
                   // real_start_date now
                   $task->real_start_date = date('Y-m-d');    
                   $task->save();
                     $mass[] = $task;   
               }
           }

       }
         return $mass;

   }

   // find the parent task
   public  function findParentTask($stage_id, $step_id, $dimension_id, $control_id)
   {
       $tasks = Task::where('stage_id', $stage_id)
           ->where('step_id', $step_id)
           ->where('dimension_id', $dimension_id)
           ->where('control_id', $control_id)
           ->where('status', 'active')
           ->get();
       foreach($tasks as $task){
           if($task->parent_task_id == 0)
               return $task;
       }
       return null;
   }
}
   
