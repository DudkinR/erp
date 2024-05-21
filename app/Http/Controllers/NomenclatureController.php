<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nomenclature;
use App\Helpers\FileHelpers as FileHelpers;
use App\Models\Type;
use App\Models\Project;
use App\Models\Struct;
use App\Models\Stage;
use App\Models\Step;
use App\Models\Task;
use App\Models\Dimension;
;

class NomenclatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $nomenclatures = Nomenclature::with('types')
        // 100 - limit
        ->limit(100)
        ->get();
       // return $nomenclatures;
        return view('nomenclatures.index', compact('nomenclatures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('nomenclatures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        $nomenclature = new Nomenclature();
        $nomenclature->name = $request->name;
        $nomenclature->article = $request->article;
        $nomenclature->description = $request->description;

        //$nomenclature->image = $request->image;
        //git$nomenclature-
        // save
        $nomenclature->save();
        return redirect()->route('nomenclaturs.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.show', compact('nomenclature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.edit', compact('nomenclature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        $nomenclature->name = $request->name;
        $nomenclature->article = $request->article;
        $nomenclature->description = $request->description;
        //$nomenclature->image = $request->image;
        //>belongsToMany
        $nomenclature->types()->detach();
        $nomenclature->types()->sync($request->types);
        // save
        $nomenclature->save();
        return redirect()->route('nomenclaturs.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $nomenclature = Nomenclature::find($id);
        $nomenclature->delete();
        return redirect()->route('nomenclaturs.index');

    }
    // createdoc
    public function createDoc(string $id)
    {
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.createDoc', compact('nomenclature'));
    }
    // nomenclatures.img.create
    public function createImg(string $id)
    {
        $nomenclature = Nomenclature::find($id);
        return view('nomenclatures.createImg', compact('nomenclature'));
    }
    // nomenclatures.img.store
    public function storeImg(Request $request)
    {
        $id = $request->nomenclature_id;
        $nomenclature = Nomenclature::find($id);
        
        // rename file and download asset('storage/nomenclature/'.$nomenclature->image
        $file = $request->file('image');
        $filename = $nomenclature->id . '.' . $file->getClientOriginalExtension();
        // delete old file any getClientOriginalExtension
        if($nomenclature->image)
        {
            $oldfile = public_path('storage/nomenclature/'.$nomenclature->image);
            if(file_exists($oldfile))
            {
                unlink($oldfile);
            }
        }

        $file->move(public_path('storage/nomenclature'), $filename);
        $nomenclature->image = $filename;

        $nomenclature->save();
        return redirect()->route('nomenclaturs.show', $id);
    }
    // import
    public function import()
    {
        return view('nomenclatures.import');
    }
    // importData
    public function importData(Request $request)
    {
        if($request->type_of_file)
            $type_of_file =$request->type_of_file;
            else
            $type_of_file = 0;
            $csvData = FileHelpers::csvToArray($request->file('file'),$type_of_file);
        if($request->type_id)
            $type_id =$request->type_id;
            else
            $type_id = 0;    
       
        foreach ($csvData as $dt) {
            $data = str_getcsv($dt, ";");
         //   return $data;
            $nomenclature = new Nomenclature();
            $nomenclature->name = $data[0];
            $nomenclature->article = $data[1];
            // save 
            $nomenclature->save();
            $nomenclature->types()-> attach($type_id);

        }
        return redirect()->route('nomenclaturs.index');
    }

    public function search(Request $request)
    {
        // Получаем параметры из запроса
        $search = $request->input('search', '');
        $type = $request->input('type'); // Используйте input для получения данных формы или query-параметров
    
        // Разделяем поисковый запрос на слова
        $searchWords = explode(' ', $search);
    
        // Осуществляем поиск по номенклатурам
        $nomenclatures = Nomenclature::query()
            ->where(function($query) use ($searchWords) {
                foreach ($searchWords as $word) {
                    $query->orWhere('name', 'LIKE', "%{$word}%")
                          ->orWhere('description', 'LIKE', "%{$word}%");
                }
            })
            ->when($type, function($query, $type) {
                // Уточняем условие поиска по типу, явно указывая, что id относится к таблице types
                return $query->whereHas('types', function($query) use ($type) {
                    $query->where('types.id', $type); // Указываем алиас types для таблицы
                });
            })
            ->with('types') // Подгружаем связанные типы сразу
            ->limit(100) // Ограничиваем количество результатов
            ->get();
    
        return response()->json($nomenclatures);
    }

    public function addNomenclatureToProject(Request $request)
    { 
       // return $request;
       
        $projectId = $request->project_id;
        $nomenclatureId = $request->nomenclature_id;
        $positionId = $request->position_id;
        $quantity = $request->quantity;
        $stageName = $request->stage_name;
        $stepName = $request->step_name;
 
        session(['project_id' => $projectId]);
        session(['nomenclature_id' => $nomenclatureId]);
        session(['position_id' => $positionId]);
        session(['quantity' => $quantity]);
        session(['stage_name' => $stageName]);
        session(['step_name' => $stepName]);
        
        $stage = Stage::find( $stageName);
        $step = Step::find($stepName);
        $project = Project::find($projectId);
        $nomenclature = Nomenclature::find($nomenclatureId);

        //$structure = Struct::find($structureId);
        $date = new \DateTime();
        $task = new Task();
        $task->project_id = $projectId;
        $task->stage_id = $stage->id;
        $task->step_id = $step->id;
        
        $dism = Dimension::where('name', 'штук')->first();
        if(!$dism)
        {
            $dism = new Dimension();
            $dism->name = 'штук';
            $dism->unit = 'шт';
            $dism->type = 'int';
            $dism->save();
        }
        // нужна ссылка на dimension которая отвечает за количество а в нее добавить количество и параметры контроля
        $task->dimension_id = $dism->id;
        $task->control_id = 0;
        $task->deadline_date = $date->modify('+1 month')->format('Y-m-d');
        $task->responsible_position_id = $positionId;
        $task->dependent_task_id = 0;
        $task->parent_task_id = 0;
        $task->real_start_date = date('Y-m-d');
        $task->real_end_date =  $date->modify('+1 month')->format('Y-m-d');
        $task->save();
        $task->nomenclatures()->attach($nomenclatureId);
        //y Struct belongsToMan positions
        $structures_responsible =  Struct::whereHas('positions', function($query) use ($positionId) {
            $query->where('positions.id', $positionId);
        })->first();
        $task->structures()->attach($structures_responsible);
        // create dimension_task
        $task->dimensions()->attach($dism->id, ['value' => $quantity, 'fact' => 0, 'status' => 'new', 'comment' => '']);
        return  response()->json(['success' => true]);
          
    }
    
}
