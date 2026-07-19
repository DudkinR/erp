<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\Type;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Jit;
use App\Models\Brief;
use App\Models\Risk;
use App\Models\Keyword;

use App\Models\Kndk;

use Illuminate\Support\Facades\Auth;    


class RiskController extends Controller
{
    // index
    public function index(Request $request)
    {
        $systemIds = $request->systems ?? System::all()->pluck('id')->toArray();
        $equipmentIds = $request->equipments ?? Type::where('slug', 'equipment')->first()->children->pluck('id')->toArray();
        $actionIds = $request->actions ?? Type::where('slug', 'action')->first()->children->pluck('id')->toArray();
        $riskIds = $request->risks ?? Risk::all()->pluck('id')->toArray();

        $experiences = Experience::where('accepted', 0)
            ->whereHas('systems', function($query) use ($systemIds) {
                $query->whereIn('systems.id', $systemIds);
            })
            ->whereHas('equipments', function($query) use ($equipmentIds) {
                $query->whereIn('types.id', $equipmentIds);
            })
            ->whereHas('actions', function($query) use ($actionIds) {
                $query->whereIn('types.id', $actionIds);
            })
            ->whereHas('risks', function($query) use ($riskIds) {
                $query->whereIn('risks.id', $riskIds);
            })
            ->orderBy('consequence', 'desc')
            ->get();

        $risk = $this->calculateRisk($experiences);

        return view('risks.index', compact('experiences', 'risk'));
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
        $risks = Risk::all();
        return view('risks.create', compact('equipments', 'systems', 'causes', 'actions', 'risks'));
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
        $experience->risks()->sync($request->risks);

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
    public function StartCardRisk()
    {
        $eventsData = [
            // 🔹 Пересування до/від робочого місця
            ['id'=>1,'work_type'=>'Пересування до/від робочого місця','name'=>'Пересування транспортом підприємства','keywords'=>['транспорт','автобус','службовий'],'severity'=>5,'frequency'=>0],
            ['id'=>2,'work_type'=>'Пересування до/від робочого місця','name'=>'Пересування громадським транспортом','keywords'=>['громадський','маршрутка','поїзд'],'severity'=>4,'frequency'=>0],
            ['id'=>3,'work_type'=>'Пересування до/від робочого місця','name'=>'Слизька або нерівна поверхня','keywords'=>['слизька','поверхня','ожеледиця'],'severity'=>3,'frequency'=>0],
            ['id'=>4,'work_type'=>'Пересування до/від робочого місця','name'=>'Недостатнє освітлення','keywords'=>['освітлення','темрява'],'severity'=>2,'frequency'=>0],
            ['id'=>5,'work_type'=>'Пересування до/від робочого місця','name'=>'Несприятливі погодні умови','keywords'=>['погода','дощ','сніг'],'severity'=>2,'frequency'=>0],

            // 🔹 Виконання посадових обов’язків
            ['id'=>6,'work_type'=>'Виконання посадових обов’язків','name'=>'Робота з документами','keywords'=>['документи','папери'],'severity'=>3,'frequency'=>0],
            ['id'=>7,'work_type'=>'Виконання посадових обов’язків','name'=>'Робота з ПЕОМ','keywords'=>['комп\'ютер','ПЕОМ','монітор'],'severity'=>2,'frequency'=>0],

            // 🔹 Відрядження
            ['id'=>8,'work_type'=>'Відрядження','name'=>'Пересування транспортом підприємства','keywords'=>['відрядження','службовий транспорт'],'severity'=>4,'frequency'=>0],
            ['id'=>9,'work_type'=>'Відрядження','name'=>'Пересування громадським транспортом','keywords'=>['відрядження','громадський транспорт'],'severity'=>3,'frequency'=>0],
            ['id'=>10,'work_type'=>'Відрядження','name'=>'Загрози агресії РФ','keywords'=>['обстріл','агресія','війна'],'severity'=>5,'frequency'=>0],

            // 🔹 Експлуатація побутових приладів
            ['id'=>11,'work_type'=>'Експлуатація побутових приладів','name'=>'Температура','keywords'=>['гарячий','опік','температура'],'severity'=>3,'frequency'=>0],
            ['id'=>12,'work_type'=>'Експлуатація побутових приладів','name'=>'Струм','keywords'=>['струм','електрика'],'severity'=>4,'frequency'=>0],
            ['id'=>13,'work_type'=>'Експлуатація побутових приладів','name'=>'Електричні мережі','keywords'=>['мережа','коротке замикання'],'severity'=>4,'frequency'=>0],

            // 🔹 Експлуатація кондиціонерів
            ['id'=>14,'work_type'=>'Експлуатація кондиціонерів','name'=>'Електричні мережі','keywords'=>['кондиціонер','електрика'],'severity'=>3,'frequency'=>0],
            ['id'=>15,'work_type'=>'Експлуатація кондиціонерів','name'=>'Переохолодження','keywords'=>['переохолодження','застуда'],'severity'=>2,'frequency'=>0],
            ['id'=>16,'work_type'=>'Експлуатація кондиціонерів','name'=>'Відсутність вентиляції','keywords'=>['вентиляція','повітря'],'severity'=>2,'frequency'=>0],
            ['id'=>17,'work_type'=>'Експлуатація кондиціонерів','name'=>'Фреон','keywords'=>['фреон','витік'],'severity'=>4,'frequency'=>0],

            // 🔹 Евакуація персоналу
            ['id'=>18,'work_type'=>'Евакуація персоналу','name'=>'Аварія радіаційна','keywords'=>['радіація','аварія'],'severity'=>5,'frequency'=>0],
            ['id'=>19,'work_type'=>'Евакуація персоналу','name'=>'Пожежа','keywords'=>['пожежа','займання'],'severity'=>4,'frequency'=>0],
            ['id'=>20,'work_type'=>'Евакуація персоналу','name'=>'Вибух','keywords'=>['вибух','детонація'],'severity'=>5,'frequency'=>0],
            ['id'=>21,'work_type'=>'Евакуація персоналу','name'=>'Захаращення шляхів евакуації','keywords'=>['шлях','евакуація','перешкода'],'severity'=>3,'frequency'=>0],
            ['id'=>22,'work_type'=>'Евакуація персоналу','name'=>'Обстріли РФ','keywords'=>['обстріл','ракета','агресія'],'severity'=>5,'frequency'=>0],
        ];

        // Вибираємо досвід за останні 2 роки
        $yearThreshold = date('Y') - 2;  
        $experiences = Experience::where('year', '>=', $yearThreshold)->get();

        foreach ($eventsData as &$event) {
            foreach ($experiences as $exp) {
                $text = mb_strtolower($exp->text_uk.' '.$exp->text_ru.' '.$exp->text_en);
                foreach ($event['keywords'] as $kw) {
                    if (str_contains($text, mb_strtolower($kw))) {
                        $event['frequency']++;
                    }
                }
            }
        }

        return view('risk', ['eventsData' => $eventsData]);
    }
   public function createform(Request $request)
    {   
        $risks = Risk::all();
        $eventsData = [];

        // усі досвіди за останні 2 роки
        $allExperiences = Experience::where('year', '>=', date('Y') - 2)->get();
        $totalAll = $allExperiences->count();

        foreach ($risks as $risk) {
            // досвіди, що містять цей ризик
            $experiences = Experience::where('year', '>=', date('Y') - 2)
                ->whereHas('risks', function($query) use ($risk) {
                    $query->where('risks.id', $risk->id);
                })
                ->get();

            $count = $experiences->count();

            // Frequency = кількість випадків
            $frequency = $count;

            // Severity = середнє consequence
            $severity = $count > 0 ? $experiences->avg('consequence') : 0;

            // Probability = частка від усіх досвідів
            $probability = $totalAll > 0 ? $count / $totalAll : 0;

            $eventsData[] = [
                'id' => $risk->id,
                'work_type' => $risk->name,
                'name' => $risk->description,
                'severity' => round($severity, 2),
                'probability' => round($probability, 2),
                'frequency' => $frequency,
            ];
        }

        return view('risks.show', compact('eventsData'));
    }



// ****************************************************************************************************************************** */
     public function indexr()
    {
        // Завантажуємо ризики разом із КНДК, до яких вони прив'язані
        $risks = Risk::with('kndks')->get();
        
        return view('r.index', compact('risks'));
    }

    /**
     * Форма створення нового ризику
     */
    public function creater()
    {
        $kndks = Kndk::all();

        return view('r.create', compact('kndks'));
    }

   public function showr($id)
    {
        $risk = Risk::with('kndks')->findOrFail($id);

        return view('r.show', compact('risk'));
    }

    public function storer(Request $request)
    {
        $validated = $request->validate([
            'risks'              => 'required|array|min:1',
            'risks.*.name'        => 'required|string|max:255',
            'risks.*.description' => 'nullable|string',
            'risks.*.kndk_ids'    => 'required|array|min:1',
            'risks.*.kndk_ids.*'  => 'exists:kndks,id',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['risks'] as $riskData) {
                // 🔎 Нормалізація назви для пошуку дубліката
                $normalizedName = mb_strtolower(
                    preg_replace('/[^a-zA-Zа-яієїґА-ЯІЄЇҐ0-9]/u', '', $riskData['name'])
                );

                // Шукаємо ризик з такою ж нормалізованою назвою
                $existingRisk = Risk::whereRaw("REPLACE(LOWER(REGEXP_REPLACE(name, '[^a-zA-Zа-яієїґА-ЯІЄЇҐ0-9]', '')), '', '') = ?", [$normalizedName])->first();

                if ($existingRisk) {
                    // Якщо знайшли — додаємо нове пояснення як абзац
                    $newDescription = trim(($existingRisk->description ?? '') . "\n\n" . ($riskData['description'] ?? ''));
                    $existingRisk->update(['description' => $newDescription]);

                    // Повністю перезаписуємо КНДК
                    $existingRisk->kndks()->sync($riskData['kndk_ids']);

                    // Синхронізація ключових слів
                    $this->syncKeywordsForRisk($existingRisk);
                } else {
                    // Якщо не знайшли — створюємо новий ризик
                    $risk = Risk::create([
                        'name'        => $riskData['name'],
                        'description' => $riskData['description'] ?? null,
                    ]);

                    $risk->kndks()->sync($riskData['kndk_ids']);

                    // Синхронізація ключових слів
                    $this->syncKeywordsForRisk($risk);
                }

            }
        });

        return redirect()->route('r.index')
            ->with('success', 'Пакет ризиків успішно внесено/оновлено. Перевірка назв виконана без дублювання.');
    }



    /**
     * Форма редагування ризику
     */
    public function editr($id)
    {
        $risk = Risk::with('kndks')->findOrFail($id);
        $kndks = Kndk::all();

        return view('r.edit', compact('risk', 'kndks'));
    }

    /**
     * ОНОВЛЕННЯ ризику та його оцінок
     */
   public function updater(Request $request, $id)
    {
        $risk = Risk::findOrFail($id);

        $validated = $request->validate([
            'risks'              => 'required|array|min:1',
            'risks.0.name'        => 'required|string|max:255',
            'risks.0.description' => 'nullable|string',
            'risks.0.kndk_ids'    => 'required|array|min:1',
            'risks.0.kndk_ids.*'  => 'exists:kndks,id',
        ]);

        $riskData = $validated['risks'][0];

        DB::transaction(function () use ($riskData, $risk) {
            $normalizedName = mb_strtolower(
                preg_replace('/[^a-zA-Zа-яієїґА-ЯІЄЇҐ0-9]/u', '', $riskData['name'])
            );

            // шукаємо схожий ризик
            $risk_similar = Risk::all()->first(function ($r) use ($normalizedName) {
                $name = mb_strtolower(preg_replace('/[^a-zA-Zа-яієїґА-ЯІЄЇҐ0-9]/u', '', $r->name));
                return $name === $normalizedName;
            });

            if ($risk_similar && $risk_similar->id !== $risk->id) {
                $newDescription = trim(($risk_similar->description ?? '') . "\n\n" . ($riskData['description'] ?? ''));
                $risk->update([
                    'name'        => $riskData['name'],
                    'description' => $riskData['description'] ?? null,
                ]);
                
              $risk->kndks()->sync($riskData['kndk_ids']);
              $risk->kndks()->syncWithoutDetaching($risk_similar->kndks->pluck('id')->toArray());
              $this->syncKeywordsForRisk($risk);
              // Видаляємо схожий ризик після перенесення опису та КНДК
                $risk_similar->delete();

            } else {
                $risk->update([
                    'name'        => $riskData['name'],
                    'description' => $riskData['description'] ?? null,
                ]);

              $risk->kndks()->sync($riskData['kndk_ids']);
                $this->syncKeywordsForRisk($risk);
            }
        });

        return redirect()->route('r.index')
            ->with('success', 'Ризик успішно модернізовано/створено.');
    }



    /**
     * Метод для ключових слів
     */
    protected function syncKeywordsForRisk(Risk $risk)
    {
         $stopWords = [
            'а', 'або', 'але', 'багато', 'би', 'біля', 'бо', 'більш', 'буде', 'будемо', 
            'будете', 'будешь', 'буди', 'була', 'були', 'було', 'бути', 'в', 'вже', 'ви', 
            'вимог', 'він', 'від', 'відповідно', 'вона', 'вони', 'воно', 'всі', 'всій', 
            'всіх', 'всієї', 'всіма', 'всьому', 'всупереч', 'де', 'для', 'до', 'доки', 
            'дуже', 'енергатом', 'енергоатом', 'є', 'за', 'завдяки', 'загалом', 'зараз', 
            'згідно', 'зі', 'зокрема', 'й', 'його', 'йому', 'і', 'із', 'інша', 
            'інше', 'інши', 'інших', 'іншим', 'іншими', 'інші', 'категорично', 'коли', 
            'коло', 'котрий', 'крім', 'куди', 'лише', 'має', 'мають', 'майже', 'мало', 
            'мене', 'метою', 'ми', 'між', 'мірі', 'мій', 'може', 'можуть', 'мов', 
            'на', 'над', 'навіть', 'наек', 'нам', 'нами', 'нас', 'наш', 'наша', 
            'наше', 'наші', 'наче', 'не', 'неї', 'нехай', 'нижче', 'них', 'ні', 
            'ніби', 'ніж', 'ніхто', 'нічого', 'ну', 'о', 'об', 'обов\'язково', 'обмежено', 
            'один', 'одна', 'однак', 'одне', 'одні', 'ось', 'офіційно', 'перед', 'під', 
            'після', 'по', 'поки', 'потім', 'при', 'про', 'проте', 'протягом', 'разі', 
            'разом', 'рік', 'років', 'року', 'році', 'саме', 'свій', 'своє', 'своєчасне', 
            'свої', 'своїх', 'себе', 'собою', 'та', 'так', 'така', 'таке', 'такі', 
            'такого', 'такому', 'також', 'там', 'твій', 'те', 'теж', 'ти', 'тим', 
            'тисяч', 'ті', 'тільки', 'то', 'тоді', 'того', 'тож', 'тому', 'тощо', 
            'треба', 'тут', 'у', 'усі', 'усіх', 'усьому', 'хаес', 'хай', 'хто', 
            'це', 'цей', 'ця', 'цих', 'цим', 'цими', 'ці', 'час', 'часу', 'через', 
            'чи', 'чий', 'чинного', 'числі', 'що', 'щоб', 'щодо', 'ще', 'я', 
            'яка', 'який', 'якого', 'якому', 'яких', 'які', 'якість', 'як', 'якби', 
            'якщо'
        ];

        $text = mb_strtolower($risk->name.' '.($risk->description ?? ''));
        preg_match_all('/[a-zA-Zа-яієїґА-ЯІЄЇҐ0-9\-]+/u', $text, $matches);

        $words = collect($matches[0] ?? [])
            ->map(fn($w) => trim($w))
            ->filter(fn($w) => !empty($w) && !in_array($w, $stopWords) && mb_strlen($w) >= 3)
            ->unique();

        if ($words->isEmpty()) return;

        $keywordIds = [];
        foreach ($words as $word) {
            $keyword = Keyword::firstOrCreate(['name' => $word]);
            $keywordIds[] = $keyword->id;
        }

        // Прив’язка ключових слів до ризику
        $risk->keywords()->syncWithoutDetaching($keywordIds);

        // Прив’язка ключових слів до КНДК
        foreach ($risk->kndks as $kndk) {
            $kndk->keywords()->syncWithoutDetaching($keywordIds);
        }
    }

    /**
     * ВИДАЛЕННЯ ризику (автоматично видалить і записи з kndk_risk завдяки onDelete('cascade'))
     */
    public function destroyr($id)
    {
        $risk = Risk::findOrFail($id);
        $risk->delete();

        return redirect()->route('r.index')
                         ->with('success', 'Ризик успішно видалено з реєстру.');
    }
 
}
