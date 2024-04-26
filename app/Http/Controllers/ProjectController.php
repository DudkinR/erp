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
    //    $project = Project::find(8);
     //   return $project->clients->first()->name;
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
        // //    //Пріоритет	Номер	Дата	Сума	Клієнт	Поточний стан	Строк виконання	% оплати	% відвантаження	% боргу	Валюта	Операція
        $project = new Project();
        $project->name = $request->name;
        $project->description = $request->description;
        $project->priority = $request->priority;
        $project->number = $request->number;
        $project->date = $request->date;
        $project->amount = $request->amount;
        $project->client = $request->client;
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
            $project->amount = $request->amount;
            $project->client = $request->client;
            $project->current_state = $request->current_state;
            $project->execution_period = $request->execution_period;
            $project->payment_percentage = $request->payment_percentage;
            $project->shipping_percentage = $request->shipping_percentage;
            $project->debt_percentage = $request->debt_percentage;
            $project->currency = $request->currency;
            $project->operation = $request->operation;
            $project->save();
            $project->stages()->sync($request->stages);
            // personal
            $project->personals()->sync($request->personals);
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
          //  return $csvData;
            foreach ($csvData as $line) {
            $data = str_getcsv($line, ";"); 
          // add project Пріоритет	Номер	Дата	Сума	Клієнт	Поточний стан	Строк виконання	% оплати	% відвантаження	% боргу	Валюта	Операція
              $project = new Project();
            $project->priority = $data[0];  
            $project->number = $data[1];
            $project->date = CommonHelper::formattedDate($data[2]);
            $project->amount = $data[3];
            $client=$this->client_find($data[4]);
            $project->client = $client;
            $project->current_state = $data[5];
            $project->execution_period = $data[6];
            $project->payment_percentage = $data[7];
            $project->shipping_percentage = $data[8];
            $project->debt_percentage = $data[9];
            $project->currency = $data[10];
            $project->operation = $data[11];
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

}
