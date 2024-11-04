<?php

namespace App\Http\Controllers;

use App\Models\Brief;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Jit;
use App\Models\Jitqw;

class JitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     
        $jits = Jit::orderBy('name_uk', 'asc')
        ->with('jitqws')
        ->get();
        return view('jits.index', compact('jits'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jitqws = Jitqw::all();
        return view('jits.create', compact('jitqws'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $jitqws = Jitqw::all();
        $jit = Jit::find($id);
        return view('jits.edit', compact('jit', 'jitqws'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
       $jit = Jit::find($id);
            $jit->name_ru = $request->name_ru;
            $jit->name_uk = $request->name_uk;
            $jit->name_en = $request->name_en;
            $jit->description_ru = $request->description_jit_ru;
            $jit->description_uk = $request->description_jit_uk;
            $jit->description_en = $request->description_jit_en;
            $jit->keywords = $request->keywords;
            $jit->num = $request->number;
            $jit->save();
            $jit->jitqws()->detach();
            if($request->jitqws){
            //    return $request->description_ru;
            $jit->jitqws()->sync($request->jitqws);
                foreach($request->jitqws as $jitqw){
                    $jitqw = Jitqw::find($jitqw);
                    if($request->description_ru[$jitqw->id])
                    $jitqw->description_ru = $request->description_ru[$jitqw->id];
                    if($request->description_uk[$jitqw->id]) 
                    $jitqw->description_uk = $request->description_uk[$jitqw->id];
                    if($request->description_en[$jitqw->id])
                    $jitqw->description_en = $request->description_en[$jitqw->id];
                    $jitqw->save();
                }
            }
            return redirect()->route('jits.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
//     $types 
    /*  
          $acts= DB::connection('mysql2')->table('actions')
        ->select('name','f','risk','made','aditional','porydok') 
        ->orderBy('porydok')      
        ->get();
         //`id`, `name_uk`, `name_ru`, `name_en`, `order`, `type`, `risk`, `functional`, `created_at`, `updated_at`
         foreach($acts as $act){
            $brief = Brief::where('name_ru', $act->name)->first();
            if(!$brief){
                $brief = new Brief();
                $brief->name_ru = $act->name;
                $brief->name_uk = '';
                $brief->name_en = '';
                $brief->order = $act->porydok;
                $brief->type = $act->f;
                $brief->risk = $act->risk;
                $brief->functional = $act->made;
                $brief->save();
            }
        }
        return Brief::all();
    
    $opyts= DB::connection('mysql2')->table('oput_bp')
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
