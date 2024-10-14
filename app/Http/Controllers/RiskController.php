<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\Type;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;    


class RiskController extends Controller
{
    // index
    public function index(Request $request)
    {
    /*   $types_eq = Type::where('parent_id', 30)->get();
       $causes =[];
       $equipments=[];
      foreach($types_eq as $type){
         // find types_couse where slug = $type->slug
         $cause_id =    Type::where('parent_id', 46)->where('slug', $type->slug)->first();
         if($cause_id){
            $causes[] = $cause_id->id;
            $equipments[] = $type->id;
            }
      }
      $experiences = Experience::all();
      foreach($experiences as $experience){
          $reasons = $experience->reasons->pluck('id')->toArray();
          // заменить в reasons $equipments на $сauses
            $new_reasons =[];
            foreach($reasons as $reason){
                if(in_array($reason, $equipments)){
                    $new_reasons = array_merge($new_reasons, $causes);
                }else{
                    $new_reasons[] = $reason;
                }
            }
            $experience->reasons()->detach();
            $experience->reasons()->sync($new_reasons);
      }
        return $experiences = Experience::all();
*/

      //  $reasons=$request->jits;  
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
      
        return $this->calculateRisk($experiences);
         view('risks.index', compact('experiences'));

    }

    public function calculateRisk($events)
    {
        $currentYear = date('Y');
        $result = 0;
        $n=0;
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->pluck('id')->toArray();
         $RS =[];
        foreach ($causes as $cause){
            $RS[$cause] = 0;
        
        }
   //return $reasons;
        
        foreach ($events as $event) {
            $yearsAgo = $currentYear - $event['year'];
    
            // Определение вероятности (В) на основе 'npp' и 'year'
            if ($event['npp'] == 3) {
                $V = 5; // актуальное для станции
            } elseif ($event['npp'] == 1) {
                $V = 3; // для страны
            } else {
                $V = 1; // общее
            }
    
            // Коррекция вероятности в зависимости от года события (чем старше, тем меньше вероятность) 0 to 100  
            if ($yearsAgo <=  2) {
                $V = $V * 0.9;
            } elseif ($yearsAgo <=  5) {
                $V = $V * 0.8;
            } elseif ($yearsAgo <= 10) {
                $V = $V * 0.6;
            } elseif ($yearsAgo <= 15) {
                $V = $V * 0.4;
            } elseif ($yearsAgo <= 20) {
                $V = $V * 0.2;
            } else {
                $V = $V * 0.1;
            }
    
            // Ограничение вероятности до 5
            $V = min($V, 5);
    
            // Тяжесть последствий (Т)
            $T = $event['consequence'];
    
            // Вычисление риска (Р)
            $R = $V * $T;
    
            // Сохранение результата
            $result+=$R;
            $n++;
           // couses 
              $reasons = $event->reasons->pluck('id')->toArray();
            foreach ($reasons as $reason){
                
                if(!isset($RS[$reason])) $RS[$reason] = 1;
                else
                $RS[$reason] += 1;
            }

        }
        $RS = array_map(function($value) use ($n){
            return $value/$n;
        }, $RS);
        
    
         return ['result'=>$result/$n, 'n'=>$n, 'reasons'=>$RS];
    }

    public function experiences()
    {
        $experiences = Experience::orderBy('npp', 'asc')->get();
        return view('risks.index', compact('experiences'));

    }
    // create
    public function create()
    {
        $equipments_parent = Type::where('slug', 'equipment')->first();
        $equipments = Type::where('parent_id', $equipments_parent->id)->get();
        $systems = System::all();
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
        $systems = System::all();
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = Type::where('parent_id', $actions_parent->id)->get();
        return view('risks.edit', compact('experience', 'equipments', 'systems', 'causes', 'actions'));
    }
    // update
    public function update(Request $request, $id)
    {
        $experience = Experience::find($id);
        $text_ru= $experience->text_ru;
        $text_uk= $experience->text_uk;
        $text_en= $experience->text_en;
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
        $experience->reasons()->sync($request->reasons);
        return redirect()->route('risks.index');
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


    
    
}
