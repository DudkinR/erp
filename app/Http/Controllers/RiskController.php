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
    public function index()
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
        $experience->reasons()->sync($request->reasons);
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


    //     $types 
    /*     $opyts= DB::connection('mysql2')->table('oput_bp')
       ->select('text','npp','system','action','equipment','year','post','cause','take') 
       ->orderBy('npp')      
       ->get();
        $Equa_uk = array('W' => 'Теплообмінне обладнання','S' => 'Арматура','P' => 'Попередні клапани','B' => 'Баки','K' => 'КІП','D' => 'Насоси','A' => 'Електродвигуни','T' => 'Трубопроводи','N' => 'Фільтри','R' => 'Регулятори','O' => 'ОР СЗЗ','E' => 'Електропостачання','C' => 'Програмне забезпечення','H' => 'Будівельні конструкції');
        $Causea_uk=array('D' => 'Документація з Помилками','P' => 'Людський фактор','T' => 'Технічна несправність','S' => 'ЗИЗи Не використання','E' => 'Компетентність персоналу','K' => 'Комунікація Погана','A' => 'Аварійна ситуація','C' => 'Адміністративний контроль Слабкий');
        asort($Equa_uk);
        $nppa_uk=array('0' =>'Зарубіжні' ,'1' =>'України' ,'2' =>'ЗАЕС');
        $posta_uk=array('1' =>'Легка' ,'2' =>'Середня' ,'3' =>'Важка' ,'4' =>'Надважка' ,'5' =>'Катастрофа');
        $opytForModel = array();
        foreach($opyts as $opyt){
        //    return $this->Equipments($opyt->equipment);
        $experience = Experience::where('text_ru', $opyt->text)->first();
        if(!$experience){
            $experience = new Experience();
            $experience->text_ru = $opyt->text;
            $experience->text_uk = '';
            $experience->text_en = '';
            $experience->npp = $this->NPP($opyt->npp,$opyt->text);
            $experience->year = $opyt->year;
            $experience->consequence =$opyt->post;
            $experience->accepted = 0;
            $experience->author_tn = 13344;
            $experience->save();
        }
        //
        $experience->systems()->detach();
        $experience->systems()->sync($this->Systems($opyt->system));
        $experience->equipments()->detach();
        
        $experience->equipments()->sync($this->Equipments($opyt->equipment));
        $experience->actions()->detach();
        $experience->actions()->sync($this->Actions($opyt->action));
        $experience->reasons()->detach();
        $experience->reasons()->sync($this->Causes($opyt->cause));

            $opytForModel[] = array(
                'text' => $opyt->text,
                'npp' => $this->NPP($opyt->npp,$opyt->text),
                'system' => $this->Systems($opyt->system),
                'action' => $this->Actions($opyt->action),
                'equipment' => $this->Equipments($opyt->equipment),
                'year' => $opyt->year,
                'post' => $opyt->post,
                'cause' => $this->Causes($opyt->cause),
                'take' => $opyt->take
            );
        }
        //Хмельницкой АЭС
        return Experience::all();
        */

   /* public function NPP($npp,$text)
    {
        if($npp == 0){
          return  $npp;
        }
        elseif($npp == 2){
            return 1;
        }
        else{
          // find in text 'Хмельницкой АЭС'
            if(strpos($text, 'Хмельницкой АЭС') !== false){
                return 2;
            }
            else{
                return 1;
            }
        }
    }
    // System
    public function Systems($sys)
    {
         $Systema = array();
        foreach(explode(',', $sys) as $sy){
            $System = System::where('abv', $sy)->first();
            if($System){
                $Systema[] =  $System->id;
            }
            else{
                $Type_sys = new System();
                $Type_sys->uk = $sy;
                $Type_sys->ru = $sy;
                $Type_sys->en = $sy;
                $Type_sys->abv = $sy;
                $Type_sys->save();
                $Systema[] = $Type_sys->id;
            }
        }
        return $Systema;
    }
    // Action
    public function Actions($acts)
    {
        $Acta = array();
        foreach(explode(',', $acts) as $act){
            $Type_act = Type::where('color', $act)->first();
            if($Type_act){
                $Acta[] = $Type_act->id;
            }
        }
        return $Acta;
    }
    // CAUSE
    public function Causes($causs)
    {
        $parent = Type::where('name', 'Причина')->first();
        if(!$parent){
            $parent = new Type();
            $parent->name = 'Причина';
            $parent->description = 'Причина - це причина виникнення ризику';
            $parent->parent_id = 0;
            $parent->slug = 'cause';
            $parent->save();
        }
        $Causea_uk=array('D' => 'Документація з Помилками','P' => 'Людський фактор','T' => 'Технічна несправність','S' => 'ЗИЗи Не використання','E' => 'Компетентність персоналу','K' => 'Комунікація Погана','A' => 'Аварійна ситуація','C' => 'Адміністративний контроль Слабкий');
        $Causea = array();
        foreach(explode(',', $causs) as $caus){
            $Type_caus = Type::where('slug', $caus)->first();
            if($Type_caus){
                $Causea[] = $Type_caus->id;
            }
            else{
                if(!isset($Causea_uk[$caus])){
                    $w=$caus;
                }
                else{
                    $w=$Causea_uk[$caus];
                }
                $Type_caus = new Type();
                $Type_caus->name = $w;
                $Type_caus->description = $w;
                $Type_caus->parent_id = $parent->id;
                $Type_caus->slug = $caus;
                $Type_caus->save();
                $Causea[] = $Type_caus->id;
            }
        }
        return $Causea;

    }
    public function Equipments($eqs)
    {
        $parent = Type::where('name', 'Тіп обладнання')->first();
        if(!$parent){
            $parent = new Type();
            $parent->name = 'Тіп обладнання';
            $parent->description = 'Тіп обладнання - це обладнання, яке використовується';
            $parent->parent_id = 0;
            $parent->slug = 'equipment';
            $parent->save();
        }
        $Equa_uk = array('W' => 'Теплообмінне обладнання','S' => 'Арматура','P' => 'Попередні клапани','B' => 'Баки','K' => 'КІП','D' => 'Насоси','A' => 'Електродвигуни','T' => 'Трубопроводи','N' => 'Фільтри','R' => 'Регулятори','O' => 'ОР СЗЗ','E' => 'Електропостачання','C' => 'Програмне забезпечення','H' => 'Будівельні конструкції');
        $Equa = array();
        foreach(explode(',', $eqs) as $eq){
            $Type_eq = Type::where('slug', $eq)->first();
            if($Type_eq){
                $Equa[] = $Type_eq->id;
            }
            else{
                if(!isset($Equa_uk[$eq])){
                    $w=$eq;
                }
                else{
                    $w=$Equa_uk[$eq];
                }
                $Type_eq = new Type();
                $Type_eq->name = $w;
                $Type_eq->description = $w;
                $Type_eq->parent_id = $parent->id;
                $Type_eq->slug = $eq;
                $Type_eq->save();
                $Equa[] = $Type_eq->id;
            }
        }
        return $Equa;
    }
*/
    
}
