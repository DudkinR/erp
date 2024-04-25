<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Stage;
use App\Models\Step;
use App\Models\Control;
use App\Models\Personal;
use App\Models\Dimension;

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

}
