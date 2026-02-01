<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\Type;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Jit;
use App\Models\Brief;
use Illuminate\Support\Facades\Auth;    


class RiskController extends Controller
{
    // index
    public function index(Request $request)
    {

      if($request->systems){
        $systemIds = $request->systems;
        }else{
            $systemIds = System::all()->pluck('id')->toArray();
        }
        if($request->equipments){
            $equipmentIds =  $request->equipments;
        }else{
            $equipmentIds = Type::where('slug', 'equipment')->first()->children->pluck('id')->toArray();
        }
        if($request->actions){
            $actionIds = $request->actions;
        }else{
            $actionIds = Type::where('slug', 'action')->first()->children->pluck('id')->toArray();
        }

        $experiences = Experience::   where('accepted',0)
       ->whereHas('systems', function($query) use ($systemIds) {
            $query->whereIn('systems.id', $systemIds);
        })
        ->whereHas('equipments', function($query) use ($equipmentIds) {
            $query->whereIn('types.id', $equipmentIds);
        })
        ->whereHas('actions', function($query) use ($actionIds) {
            $query->whereIn('types.id', $actionIds);
        })
        ->orderBy('consequence' , 'desc')
        ->get();
      
        $risk = $this->calculateRisk($experiences);
       return  view('risks.index', compact('experiences', 'risk'));
    }

    public function StartBriefRisk(){
        // equipment
        $equipments_parent = Type::where('slug', 'equipment')->first();
        $equipments = collect(); // порожня колекція за замовчуванням
        if ($equipments_parent) {
            $equipments = Type::where('parent_id', $equipments_parent->id)->get();
        }

        // systems
        $systems = System::all()->keyBy('id')->values();

        // actions
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = collect();
        if ($actions_parent) {
            $actions = Type::where('parent_id', $actions_parent->id)->get();
        }

        // addition actions
        $addition_actions = Jit::all()->keyBy('id')->values();

        // briefs
        $briefs = Brief::orderBy('order', 'asc')
            ->with('actions', 'jitqws', 'reasons')
            ->get();

        return view('risks.risks', compact(
            'equipments',
            'systems',
            'actions',
            'addition_actions',
            'briefs'
        ));
    }
    // currentRisk
    public function currentRisk(Request $request)
    {
        if($request->systems){
            $systemIds = $request->systems;
            }else{
                $systemIds = System::all()->pluck('id')->toArray();
            }
            if($request->equipments){
                $equipmentIds =  $request->equipments;
            }else{
                $equipmentIds = Type::where('slug', 'equipment')->first()->children->pluck('id')->toArray();
            }
            if($request->actions){
                $actionIds = $request->actions;
            }else{
                $actionIds = Type::where('slug', 'action')->first()->children->pluck('id')->toArray();
            }
            $experiences = Experience::   where('accepted',0)
           ->whereHas('systems', function($query) use ($systemIds) {
                $query->whereIn('systems.id', $systemIds);
            })
            ->whereHas('equipments', function($query) use ($equipmentIds) {
                $query->whereIn('types.id', $equipmentIds);
            })
            ->whereHas('actions', function($query) use ($actionIds) {
                $query->whereIn('types.id', $actionIds);
            })
            ->with('systems', 'equipments', 'actions', 'reasons')
            ->orderBy('consequence' , 'desc')
            ->get();
          
            $risk = $this->calculateRisk($experiences);
           return  ['experiences'=>$experiences, 'risk'=>$risk];
    }

    public function calculateRisk($events)
    {
        $currentYear = date('Y');
        $result = 0;
        $n = 0;
        $ncouses = 1;
    
        // Отримуємо список причин
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->pluck('id')->toArray();
        $RS = array_fill_keys($causes, 0);
       //  $events;

        foreach ($events as $event) {
            $yearsAgo = $currentYear - $event['year'];
    
            // Визначення ймовірності (V)
            if ($event['npp'] == 3) {
                $V = 7; // Актуальне для станції
            } elseif ($event['npp'] == 1) {
                $V = 3; // Для країни
            } else {
                $V = 1; // Загальне
            }
    
            // Корекція ймовірності залежно Вид віку події
            if ($yearsAgo <= 1) {
                $V *= 1;
            } elseif ($yearsAgo <= 2) {
                $V *= 0.9;
            } elseif ($yearsAgo <= 5) {
                $V *= 0.8;
            } elseif ($yearsAgo <= 10) {
                $V *= 0.6;
            } elseif ($yearsAgo <= 15) {
                $V *= 0.4;
            } elseif ($yearsAgo <= 20) {
                $V *= 0.2;
            } else {
                $V *= 0.1;
            }
    
            // Обмеження ймовірності до 7
            $V = min($V, 7);
    
            // Тяжкість наслідків (T)
             $T = $event['consequence'];
    
            // Розрахунок ризику (R)
            $R = $V * $T;
    
            // Додавання до загального результату
            $result += $R;
            $n++;
           
            // Обробка причин
            foreach($event->reasons as $reason){
                $causes = $reason->id;
                if (!isset($RS[$causes])) {
                    $RS[$causes] = 1;
                } else {
                    $RS[$causes]++;
                }
                $ncouses++;
            }
           
        }
            // Перевірка, чи є події
        if ($n === 0) {
            return ['result' => 0, 'n' => 0, 'reasons' => []];
        }
    
        // Обчислення відсотків для причин
        $RS = array_map(function ($value) use ($ncouses) {
            return ($value * 100) / $ncouses;
        }, $RS);
    
        // Повернення результатів
        return ['result' => $result / $n, 'n' => $n, 'reasons' => $RS];
    }
    

    public function experiences()
    {
        $experiences = Experience::orderBy('text_uk', 'asc')->get();
        return view('risks.index', compact('experiences'));

    }
    // create
    public function create()
    {
        $equipments_parent = Type::where('slug', 'equipment')->first();
        $equipments = Type::where('parent_id', $equipments_parent->id)->get();
        $systems = System::all()->keyBy('id')->values();
       $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = Type::where('parent_id', $actions_parent->id)->get();
        return view('risks.create', compact('equipments', 'systems', 'causes', 'actions'));
    }

    // store
    public function store(Request $request)
    {
        $experience = new Experience();
        $text_ru='';
        $text_uk='';
        $text_en='';
        if($request->lang && $request->lang=='ru'){
            $text_ru = $request->text;            
            $accepted=0;
        }
        if($request->lang && $request->lang=='uk'){
            $text_uk = $request->text;            
            $accepted=1;
        }
        if($request->lang && $request->lang=='en'){
            $text_en = $request->text;            
            $accepted=0;
        }
       // return $request->causes;
        $experience->text_ru = $text_ru;
        $experience->text_uk = $text_uk;
        $experience->text_en = $text_en;
        $experience->npp = $request->npp;
        $experience->year = $request->year;
        $experience->consequence = $request->consequence;
        $experience->accepted = $accepted;
        $experience->author_tn =Auth::user()->tn;
        $experience->save();
        $experience->systems()->sync($request->systems);
        $experience->equipments()->sync($request->equipments);
        $experience->actions()->sync($request->actions);
        $experience->reasons()->sync($request->causes);
        return redirect()->route('risks.index');
    }
    // edit
    public function edit($id)
    {
       $experience = Experience::with('systems', 'equipments', 'actions', 'reasons')
     ->findOrFail($id);
      
        $equipments_parent = Type::where('slug', 'equipment')->first();
        $equipments = Type::where('parent_id', $equipments_parent->id)->get();
        $systems = System::all()->keyBy('id')->values();
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = Type::where('parent_id', $actions_parent->id)->get();
        return view('risks.edit', compact('experience', 'equipments', 'systems', 'causes', 'actions'));
    }
    // update
    public function update(Request $request, $id)
    {
       // return $request;
        $experience = Experience::find($id);
        $text_ru= $request->text_ru;
        $text_uk= $request->text_uk;
        $text_en= $request->text_en;
        
        $experience->text_ru = $text_ru;
        $experience->text_uk = $text_uk;
        $experience->text_en = $text_en;
        $experience->npp = $request->npp;
        $experience->year = $request->year;
        $experience->consequence = $request->consequence;
        $experience->accepted = 2;
        $experience->author_tn =Auth::user()->tn;
        $experience->save();
        // dethach and attach
        $experience->systems()->detach();
        $experience->systems()->sync($request->systems);
        $experience->equipments()->detach();
        $experience->equipments()->sync($request->equipments);
        $experience->actions()->detach();
        $experience->actions()->sync($request->actions);
        $experience->reasons()->detach();
        $experience->reasons()->sync($request->causes);
        return redirect()->route('experiences');
    }
    // destroy
    public function destroy($id)
    {
        $experience = Experience::find($id);
        $experience->systems()->detach();
        $experience->equipments()->detach();
        $experience->actions()->detach();
        $experience->reasons()->detach();
        $experience->delete();
        return redirect()->route('risks.index');
    }

    //risksPrintBrief
    public function risksPrintBrief(Request $request)
    {
      
        if( $request->equipments){
            $equipmentModels =  Type::whereIn('id', $request->equipments)->get();
        }else{
            $equipmentModels = [];
        }
        if($request->systems){
            $systemModels = System::whereIn('id', $request->systems)->get();
        }else{
            $systemModels = [];
        }
        if($request->action){
            $actionModel = Type::find($request->action);
        }else{
            $actionModel = null;
        }
        if($request->addition_actions){
            $addition_actions = Jit::whereIn('id', $request->addition_actions)->get();
        }else{
            $addition_actions = [];
        }
        if($request->experience){
            $experiences = Experience::whereIn('id', $request->experience)->get();
        }else{
            $experiences = [];
        }
        // full_name 
        if($request->full_name){
            $full_name = $request->full_name;
        }else{
            $full_name = '';
        }
        // tn
        if($request->tn){
            $tn = $request->tn;
        }else{
            $tn = Auth::user()->tn;
        }
        // fio
        if($request->fio){
            $fio = $request->fio;
        }else{
            $fio = Auth::user()->personal->fio;
        }
        // place
        if($request->place){
            $place = $request->place;
        }else{
            $place = '';
        }
        // date
        if($request->date){
            $date = $request->date;
        }else{
            $date = date('Y-m-d');
        }
        // br_action all breifModels
        $briefs = Brief::orderBy('order', 'asc')
        ->whereIn('id', array_keys($request->br_action))
        ->with('actions', 'jitqws', 'reasons')
        ->get();
        $risk = $request->risk;
        $reasons = $request->reasons;
        return view('risks.print', compact('briefs', 'risk', 'reasons', 'equipmentModels', 'systemModels', 'actionModel', 'addition_actions', 'full_name', 'tn', 'fio', 'place', 'date', 'experiences'));

    }

    // import
    public function import()
    {
        $equipments_parent = Type::where('slug', 'equipment')->first();
        $equipments = Type::where('parent_id', $equipments_parent->id)->get();
        $systems = System::all()->keyBy('id')->values();
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = Type::where('parent_id', $actions_parent->id)->get();
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $addition_actions = Jit::all()->keyBy('id')->values();
        // without reasons
        $experiences = Experience::whereDoesntHave('reasons')->get();

        return view('risks.import' , compact('equipments', 'systems', 'causes', 'actions', 'addition_actions', 'experiences', 'causes'));
    }
    public function importData(Request $request)
    {
        

        $causesID = $request->causes;
        $causes = Type::whereIn('id', $causesID)->get();
      
        $Search_word = $request->Search_word;
        foreach($causes as $cause){
        // if not 'couse' == $cause->name, 'words' == $Search_word
            if(!DB::table('prom_cousesid')->where('couse', $cause->name)->where('words', $Search_word)->exists()){
            
                DB::table('prom_cousesid')->insert(
                    ['id' =>NULL, 'couse' => $cause->name, 'words' => $Search_word]
                );
            }

        }

        $Ar_causes = $causes->pluck('couse')->toArray();
        
        $words = explode(' ', $Search_word);
        // find all experiences where text_ru or text_uk contains words
        $experiences = Experience::where(
            function($query) use ($Search_word){
 
                    $query->orWhere('text_ru', 'like', '%'.$Search_word.'%');
                    $query->orWhere('text_uk', 'like', '%'.$Search_word.'%');
 
            }
        )->get();
        foreach($experiences as $experience){

            $experience->reasons()->sync($causesID);
        }
         //return import
        return redirect()->route('risks.import');
    }
    // reimport
    public function reimport()
    {
        // Retrieve all records from prom_cousesid table
        $causes = DB::table('prom_cousesid')->get();
        $types = [];
    
        foreach ($causes as $cause) {
            // Check if the type is already in the $types array
            if (!isset($types[$cause->couse])) {
                // Retrieve and cache the type ID for the cause if not already cached
                $type = Type::where('name', $cause->couse)->first();
                $types[$cause->couse] = $type ? $type->id : null;
            }
    
            // Proceed only if a valid type ID was found
            if ($types[$cause->couse]) {
                $searchWord = $cause->words;
                $experiences = Experience::where(function ($query) use ($searchWord) {
                    $query->orWhere('text_ru', 'like', '%' . $searchWord . '%')
                          ->orWhere('text_uk', 'like', '%' . $searchWord . '%');
                })->get();
    
                foreach ($experiences as $experience) {
                    // Sync the reason with the type ID, ensure it's in array format
                    $experience->reasons()->sync([$types[$cause->couse]]);
                }
            }
        }
    
        return redirect()->route('risks.import');
    }


    
    
}
