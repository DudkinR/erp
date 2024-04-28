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
use App\Helpers\FileHelpers as FileHelpers;
use App\Helpers\CommonHelper as CommonHelper;

class ProjectController extends Controller
{
    // index
    public function index()
    {
        $projects = Project::all();
        return view('projects.index', compact('projects'));
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
            $csvData = FileHelpers::csvToArray($request->file('file'));
            $mass_d = []; // Создаем массив для хранения повторяющихся названий
            foreach ($csvData as $line) {
                $data = str_getcsv($line, ";"); 
                if ($project = Project::where('number', 'like', $data[1])->first()) {
                    if (in_array($data[1], $mass_d)) {
                        // Если название уже встречается, добавляем его в массив повторяющихся названий
                        $mass_d[] = $data[1];
                        // Получаем количество повторений для данного названия
                        $count_values = array_count_values($mass_d);
                        $count_data = isset($count_values[$data[1]]) ? $count_values[$data[1]] : 0;
                        $name = $project->name . "/" . $count_data;
                        $description = $project->description . "/" . $count_data;
                    } else {
                        // Если название встречается впервые, добавляем его в массив повторяющихся названий
                        $mass_d[] = $data[1];
                        $name = $project->name;
                        $description = $project->description;
                    }
                } else {
                    // Если проект с таким названием не найден, создаем новый проект
                    $project = new Project();
                    $name = $data[1] . " " . $data[4];
                    $description = $data[1] . " " . $data[4];
                }
                $project->name = $name;
                $project->description = $description;
                $project->priority = (int)$data[0];  
                $project->number = $data[1];
                $project->date = CommonHelper::formattedDate($data[2]);
                $project->amount =(int) $data[3];
                $client=$this->client_find($data[4]);
                $project->client = $client;
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
                $project->clients()->attach($client);
             }
             
            return redirect('/projects')->with('success', 'Projects imported!');
        }

        public function client_find($name)
        {
            $client = Client::where('name', $name)->first();
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
            // return success message
            return response()->json(['success' => 'Stage added!']);
        }

}
