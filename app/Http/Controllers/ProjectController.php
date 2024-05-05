<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Stage;
use App\Models\Step;
use App\Models\Control;
use App\Models\Personal;
use App\Models\Dimension;
use App\Models\Client;
use App\Models\Task;
use App\Helpers\FileHelpers as FileHelpers;
use App\Helpers\CommonHelper as CommonHelper;

class ProjectController extends Controller
{
    // index
    public function index()
    {
        $projects = Project::with('problems')->withCount('problems')->get();
        $clients = Client::all();
        return view('projects.index', compact('projects', 'clients'));
    }
    // create
    public function create()
    {
        $stages = Stage::all();
        $steps = Step::all();
        $controls = Control::all();
        $personals = Personal::all();
        $dimensions = Dimension::all();
        return view('projects.create', compact('stages', 'steps', 'controls', 'personals', 'dimensions'));
    }
    // store
    public function store(Request $request)
    {
               if($request->new_client!=="" && $request->client==0){
           $client=$this->client_find($request->new_client);      
        }
        else{
            $client=$request->client;     
        }
         $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->priority = $request->priority;
        $project->number = $request->number;
        $project->date = $request->date;
        $project->amount = $request->amount;
        $project->client = $client;
        $project->current_state = $request->current_state;
        $project->execution_period = $request->execution_period;
        $project->payment_percentage = $request->payment_percentage;
        $project->shipping_percentage = $request->shipping_percentage;
        $project->debt_percentage = $request->debt_percentage;
        $project->currency = $request->currency;
        $project->operation = $request->operation;
        $project->save();
        $project->stages()->attach($request->stages);
        // personal
        $project->personals()->attach($request->personals);

        return redirect('/projects')->with('success', 'Project created!');

    }
  
    // show
    public function show($id)
    {
        $project = Project::find($id);
        return view('projects.show', compact('project'));
    }
     // edit
        public function edit($id)
        {
            $project = Project::find($id);
            $stages = Stage::all();
            $steps = Step::all();
            $controls = Control::all();
            $personals = Personal::all();
            $dimensions = Dimension::all();
            return view('projects.edit', compact('project', 'stages', 'steps', 'controls', 'personals', 'dimensions'));
        }

        // update
        public function update(Request $request, $id)
        {
            $project = Project::find($id);
            $project->name = $request->name;
            $project->description = $request->description;
            $project->priority = $request->priority;
            $project->number = $request->number;
            $project->date = $request->date;
            $project->current_state = $request->current_state;
            $project->execution_period = $request->execution_period;
            $project->save();
            $project->stages()->sync($request->stages);
            // personal
            $project->personals()->sync($request->personals);
            // to projects.show
            return redirect('/projects/' . $id.'/edit')->with('success', 'Project updated!');
        }
        // destroy
        public function destroy($id)
        {
            $project = Project::find($id);
            $project->stages()->detach();
            $project->personals()->detach();
            $project->clients()->detach();
            $project->delete();
            return redirect('/projects')->with('success', 'Project deleted!');
        }
        // import
        public function import()
        {
            return view('projects.import');
        }
        // importData
        public function importData(Request $request)
        {   
            if($request->type_of_file)
            $type_of_file =$request->type_of_file;
            else
            $type_of_file = 0;
            $csvData = FileHelpers::csvToArray($request->file('file'),$type_of_file);
            $mass_d = []; // Создаем массив для хранения повторяющихся названий
            $bad_clients = []; // Создаем массив для хранения названий клиентов, которые не были найдены
            foreach ($csvData as $line) {
                $data = explode(";", $line);
                if ($project = Project::where('number', 'like', $data[1])->first()) {
                    if (in_array($data[1], $mass_d)) {
                        // Если название уже встречается, добавляем его в массив повторяющихся названий
                        $mass_d[] = $data[1];
                        // Получаем количество повторений для данного названия
                        $count_values = array_count_values($mass_d);
                        $count_data = isset($count_values[$data[1]]) ? $count_values[$data[1]] : 0;
                        $new_name = $project->name . "/" . $count_data;
                        $new_description = $project->description . "/" . $count_data;
                        // Обновляем данные проекта
                        $project->name = $new_name;
                        $project->description = $new_description;
                    } else {
                        // Если название встречается впервые, добавляем его в массив повторяющихся названий
                        $mass_d[] = $data[1];
                    }
                } else {
                    // Если проект с таким названием не найден, создаем новый проект
                    $project = new Project();
                    $project->name = $data[1] . " " . $data[4];
                    $project->description = $data[1] . " " . $data[4];
                }
                
                // Установка остальных значений проекта
  
                $project->priority = (int)$data[0];  
                $project->number = $data[1];
                $project->date = CommonHelper::formattedDate($data[2]);
                $project->amount =(int) $data[3];
                $client=$this->client_find($data[4]);
                // проверим что имя клиента не  совпадает с $data[4]
                $clt=Client::find($client);
                if($clt->name!=$data[4]){
                    $bad_clients[]=[$data[4],$clt->name];
                }
                else{
                    $project->client = $client;
                }
                $project->current_state = $data[5];
                if(isset($data[6])){
                $project->execution_period = CommonHelper::formattedDate($data[6]);
                }
                if(isset($data[7])){
                $project->payment_percentage = (int)$data[7];
                }
                if(isset($data[8])){
                $project->shipping_percentage = (int)$data[8];
                }
                if(isset($data[9])){
                $project->debt_percentage = (int)$data[9];
                }
                if(isset($data[10])){
                $project->currency = $data[10];
                }
                if(isset($data[11])){
                $project->operation = $data[11];
                }
                $project->save();
                // add attach client 
                if($clt->name==$data[4]){
                $project->clients()->attach($client);
                }
             }
                if(count($mass_d)>0){
                    return redirect('/projects')->with('error', 'Projects imported!')->with('mass_d', $mass_d);
                    }
                    else{
                return redirect('/projects')->with('success', 'Projects imported!');
                    }
        }

        public function client_find($name)
        {
            $client = Client::where('name','like', $name)->first();
            if($client){
                return $client->id;
            }else{
                $client = new Client();
                $client->name = $name;
                $client->save();
                return $client->id;
            }
        }
        // add_stage
        public function add_stage(Request $request)
        {
            $project = Project::find($request->project_id);
            $project->stages()->attach($request->stage_id); 
            $commonHelper = new CommonHelper();
          return   $commonHelper->addNewStages($request);
            $tasks = Task::where('project_id', $request->project_id)->get();
            return $tasks;
            
            redirect('/projects/' . $request->project_id)->with('success', 'Stage added!');
        }
        // add_stage_form
        public function add_stage_form()
        {
            
            return view('projects.formproject');
        }

        //projectstgantt
        public function projectstgantt($id)
        {
            $project = Project::find($id);
            $tasks = Task::where('project_id', $id)->get();
            return view('projects.projectstgantt', compact('project', 'tasks'));
        }

}
