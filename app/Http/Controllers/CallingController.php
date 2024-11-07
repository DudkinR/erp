<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calling;
use App\Models\Personal;
use App\Models\Division;
use App\Models\Type;
//Carbon
use Carbon\Carbon;
use Illuminate\Console\View\Components\Warn;
use Illuminate\Support\Facades\Auth;

class CallingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public $status_arr = [
        "user"=>"created", 
        "supervision"=>"supervision",
        "workshop-chief"=>"workshop-chief",
        "SVNtaPB"=>"SVNtaPB",
        "Profkom"=>"Profkom",
        "VONtaOP"=>"VONtaOP"];

    public function index(Request $request)
    {
       // Get the collection of started callings
       $type = $request->type ?? null;
       $start = $request->start ?? null;
       $finish = $request->finish ?? null;
       $filter = $request->filter ?? null;  // Ensuring it's either the filter or null
   
       if(Auth::user()->hasRole('VONtaOP')){
            $callings = $this->filters($filter)
            ->where('status', 'VONtaOP')->
            with(['workers.divisions','workers.positions'])->orderBy('id', 'asc')->get()->keyBy('id');
            return view('callings.VONtaOP', compact('callings','filter'));
        }        
        elseif(Auth::user()->hasRole('Profkom')){
            $callings = $this->filters($filter)
            ->where('status', 'Profkom')->
            with(['workers.divisions','workers.positions'])->orderBy('id', 'asc')->get()->keyBy('id');
            return view('callings.Profkom', compact('callings','filter'));
        }        
        elseif(Auth::user()->hasRole('SVNtaPB')){
            $callings = $this->filters($filter)
            ->where('status', 'SVNtaPB')->
            with(['workers.divisions','workers.positions'])
            ->orderBy('id', 'asc')
            ->get()->keyBy('id');            
            return view('callings.SVNtaPB', compact('callings','filter'));
        } 
        elseif(Auth::user()->hasRole('workshop-chief')){
            // Отримати всі підрозділи, до яких належить начальник
            $userDivisionIds = Auth::user()->personal->divisions()->pluck('division_id');

            // Вибрати виклики, де статус 'workshop-chief' і є працівники з тими ж підрозділами, що й начальник
            $callings =$this->filters($filter)
            ->where('status', 'workshop-chief')
             /*   ->where(function ($query) {
                    $query->where('author_id', Auth::user()->personal->id)
                        ->orWhereHas('workers', function ($query) {
                            $query->where('personal_id', Auth::user()->personal->id);
                        });
                })
                ->whereHas('workers.divisions', function ($query) use ($userDivisionIds) {
                    $query->whereIn('division_id', $userDivisionIds);
                })*/
                ->with(['workers.divisions', 'workers.positions'])
                ->orderBy('id', 'asc')
                ->get()
                ->keyBy('id');

            return view('callings.workshop_chief', compact('callings','filter'));

        }        
        elseif(Auth::user()->hasRole('supervision')){

            $callings = $this->filters($filter)
            ->where('status', 'supervision')
            ->orwhere('personal_start_id',null)
            ->orwhere('personal_arrival_id',null)
            ->orwhere('personal_end_id',null)
         //   ->with(['workers.divisions'])           
             ->orderBy('id', 'asc')->get()->keyBy('id');
            return view('callings.supervision', compact('callings','filter'));
        }
        elseif( Auth::user()->hasRole('user')){
         $userPersonalId = Auth::user()->personal->id;
         $callings = $this->filters($filter)
         ->where(function ($query) {
            $query->where('author_id', Auth::user()->personal->id)
                  ->orWhereHas('workers', function ($query) {
                      $query->where('personal_id', Auth::user()->personal->id);
                  });
        })
        ->whereIn('status', ['created', 'supervision'])
        ->with(['workers.divisions'])
        ->orderBy('id', 'asc')
        ->get()
        ->keyBy('id');

              return view('callings.index', compact('callings','filter'));
        }

  
    }

    public function filters($param)
    {
        $query = Calling::query();
    
        if ($param == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($param == 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($param == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month);
        } elseif ($param == 'in_sup') {
            $query->where('status', 'supervision');
        } elseif ($param == 'in_work') {
            $query->where('end_time', null);
        } elseif ($param == 'not_started') {
            $query->where('start_time', null);
        } elseif ($param == 'completed') {
            $query->where('status', 'completed');
        } elseif ($param == 'in_boss') {
            $query->where('status', 'workshop-chief');
        } elseif ($param == 'in_svn') {
            $query->where('status', 'SVNtaPB');
        } elseif ($param == 'in_profcom') {
            $query->where('status', 'Profkom');
        } elseif ($param == 'in_vonop') {
            $query->where('status', 'VONtaOP');
        }
    
        return $query;
    }
    

    
    public function printOrder(Request $request){
        if(!Auth::user()->hasRole('VONtaOP')){
            return redirect()->route('callings.index');
        } 
        $callings = Calling::where('status', 'for_print')->
        with(['workers.divisions'])->orderBy('id', 'asc')->get()->keyBy('id');
        return view('callings.FormOrder', compact('callings'));       
    }
     public function callingsOrder(Request $request){
       $callings=Calling::whereIn('id',$request->call_)
        ->orderBy('type_id','asc')
        ->get()->keyBy('id');

        $Workings=[];
        $divisions =[];
        
        foreach ($callings as $call) {
            $times=$this->count_time($call->start_time, $call->end_time);
            foreach ($call->workers as $worker) {
                // Check if the worker and type_id combination exists in the array
                if (!isset($Workings[$worker->id][$call->type_id])) {
                    // Initialize the worker's data for this call type
                    $Workings[$worker->id][$call->type_id] = [
                        "tn" => $worker->tn,
                        "type" => $call->type_id,
                        "pib" => $worker->fio,
                        "position" => $worker->positions[0]->name,
                        "division" => $worker->divisions[0]->name ,
                        "time" => $times['total_time'],
                        "night_time" => $times['total_night_time'],                   ];
                } else {
                    // If already set, increment time and night_time
                    $Workings[$worker->id][$call->type_id]["time"] +=  $times['total_time'];
                    $Workings[$worker->id][$call->type_id]["night_time"] += $times['total_night_time'];
                }
            }
        }
        $DI = $this->publicInformation();
      // return $Workings;
        return view('callings.ORDER', compact('callings','Workings','DI'));
     }
     public function count_time($start, $finish)
     {
         /*
         Считаем сколько часов если более 8 часов вычитаем 1 час обеда 
         также высчитываем с этого времени ночное время с 22 до 6  
         */
         // Перетворюємо строки у об'єкти Carbon
         $start = Carbon::parse($start);
         $finish = Carbon::parse($finish);
     
         // Загальний час між початком і кінцем
         $total_time = $finish->diffInHours($start);
     
         // Визначаємо нічний час
         $total_night_time = 0;
         
         // Період з 22:00 до 6:00 наступного дня
         $start_night = $start->copy()->setTime(22, 0);  // Початок ночного часу (22:00)
         $finish_night = $start->copy()->setTime(6, 0)->addDay();  // Кінець нічного часу (6:00 наступного дня)
     
         // Рахуємо нічний час
         if ($start < $finish_night && $finish > $start_night) {
             $night_start = $start->max($start_night);
             $night_end = $finish->min($finish_night);
             $total_night_time = $night_end->diffInHours($night_start);
         }
     
         return [
             'total_time' => $total_time,
             'total_night_time' => $total_night_time
         ];
     }
   
    public function store(Request $request)
    {

      //  return $request;
        $Oplata_pratsi = $request->payments;

        $calling = new Calling();
        $calling->status = 'created';
        $filling=0;
       $calling->author_id=Auth::user()->personal->id;
        if($request->description){
            $calling->description = $request->description;

            $calling->save();
            $filling++;
        }
       
        if($request->arrival_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->arrival_time);
            $calling->arrival_time = $Time;
            $calling->save();
            $filling++;
        }
         if($request->start_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time);
            $start_time=$Time;
         $calling->start_time = $Time;
            $calling->save();
            $filling++;
        }else  $start_time=null;

        if($request->end_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time);
             $end_time=$Time;
            $calling->end_time =$Time;
            $calling->save();
            $filling++;
        }else  $end_time=null;
        if($request->Type_of_work){
            $calling->type_id = $request->Type_of_work;
            $calling->save();
            $filling++;
        }
        if($request->workers){
            // find type where slug is Kerivnyk-bryhady
            $Kerivnyk_bryhady = Type::where('slug', 'Kerivnyk-bryhady')->first();
            $Robitnyky = Type::where('slug', 'Robitnyky')->first();
            $filling++;
            
            foreach($request->workers as $worker){
                if(!$request->start_timew[$worker])
                     $start_timew=$start_time;
                else{
                    $start_timew= \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_timew[$worker]);
                    if($start_timew<$calling->start_time){
                        $calling->start_time=$start_timew;
                        $calling->save();
                    }
                    if($start_timew<$calling->arrival_time){
                        $calling->arrival_time=$start_timew;
                        $calling->save();
                    }
                }    
                if(!$request->end_timew[$worker])
                    $end_timew=$end_time;
               else{
                   $end_timew= \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_timew[$worker]);
                   if($end_timew>$calling->end_time){
                    $calling->end_time=$end_timew;
                    $calling->save();
                    }
               }

                if($request->chief==$worker){
                    $calling->workers()->attach($worker,
                     ['worker_type_id' => $Kerivnyk_bryhady->id,
                     'payment_type_id' => $Oplata_pratsi[$worker], 
                     'comment' => $request->comments[$worker],
                     'start_time' => $start_timew,
                     'end_time' =>  $end_timew]);
                }else{
                    $calling->workers()->attach($worker, ['worker_type_id' => $Robitnyky->id,
                     'payment_type_id' => $Oplata_pratsi[$worker], 
                     'comment' => $request->comments[$worker],
                    'start_time' =>$start_timew,
                    'end_time' =>  $end_timew]);
                }
            }
        }
        // если все поля заполнены и со временем все ок то ставим статус supervision
        if ($this->validateTimes($calling->arrival_time,  $calling->start_time, $calling->end_time) && $filling==6) {
            $calling->status = 'supervision';
            $calling->save();
        }
          $filling;
        return redirect()->route('callings.index');
    }
    // reserveStore short store only tn  Kerivnyk_bryhady and description
    public function reserveStore(Request $request)
    {
        $calling = new Calling();
        $calling->status = 'created';
        $calling->author_id=Auth::user()->personal->id;
        $calling->arrival_time = now();
        $calling->description = $request->description;
        $calling->save();
        $worker=Personal::where('tn',$request->tab_number)->first();
        $Kerivnyk_bryhady = Type::where('slug', 'Kerivnyk-bryhady')->first();
        $calling->workers()->attach($worker->id, ['worker_type_id' => $Kerivnyk_bryhady->id, 'payment_type_id' =>0, 'comment' => null, 'start_time' => NULL, 'end_time' => NULL]);
        return redirect()->route('callings.index');
    }
    
    public function validateTimes($arrival_time, $start_time, $end_time)
    {
        // Проверяем, что все три времени заданы
        if (!$arrival_time || !$start_time || !$end_time) {
            return false; // Если одно из времен не задано — ошибка
        }
    
        // Преобразуем входные значения в объекты Carbon
        try {
            $startTime = \Carbon\Carbon::parse($arrival_time);
            $workTime = \Carbon\Carbon::parse($start_time);
            $endTime = \Carbon\Carbon::parse($end_time);
        } catch (\Exception $e) {
            // Если формат неверный — ошибка
            return $e->getMessage(); // Вернем сообщение об ошибке
        }
    
        // Проверяем логическую последовательность времени
        if ($startTime->gt($workTime) || $workTime->gt($endTime)) {
            return false; // Ошибка логики времени
        }
    
        return true; // Время корректно
    }
    
    
    // getPersonalForTN
    public function getPersonalForTN(Request $request)
    {
        $tn = $request->tn;
        $personal = Personal::with(['positions'])->where('tn', $tn)->first();
        if ($personal) {
            return response()->json([$personal]);
        }
        return response()->json([null]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $calling = Calling::find($id);
         $workers = $calling->workers;
         $checkins= $calling->checkins;
        $DI = $this->publicInformation();
        return view('callings.show', ['calling' => $calling, 'workers' => $workers, 'checkins' => $checkins,'DI' => $DI]);
    }
    public function comments(Calling $calling){
        
    }
    // confirmSS
    public function confirmSS(Request $request)
    {
         Auth::user()->personal->id;
        $calling = Calling::find($request->calling_id);   
        if(Auth::user()->hasRole('VONtaOP')){
            if($request->tp_check=='VONtaOP'){ 
            $calling ->status = 'for_print';
            $calling->save();
            }
        }        
        elseif(Auth::user()->hasRole('Profkom')){
           if($request->tp_check=='Profkom'){ 
            $calling ->status = 'VONtaOP';
            $calling->save();
           }
        }        
        elseif(Auth::user()->hasRole('SVNtaPB')){
            if($request->tp_check=='SVNtaPB'){$calling->status = 'Profkom';$calling->save();}
        } 
        elseif(Auth::user()->hasRole('workshop-chief')){
            if($request->tp_check=='workshop_chief'){
            $calling->status = 'SVNtaPB';
           $calling->save();}
        }        
        if($calling){
            $calling->checkins()->attach(Auth::user()->personal->id, [
                'checkin_type_id' => $request->checkin_type_id, 
                'type' => 1,
                'comment' => $request->comment,
                'created_at' => now(), 
                'updated_at' => now()]);
        }
        return redirect()->route('callings.index');
    }
//rejectSS
    public function rejectSS(Request $request)
    {

        $calling = Calling::find($request->calling_id);     
        if($calling){
            $calling->status = 'created'; 
            $calling->save();
            $calling->checkins()->attach(Auth::user()->personal->id, [
                'checkin_type_id' => $request->checkin_type_id, 
                'type' => 0, 
                'comment' => $request->comment,
            'created_at' => now(),
             'updated_at' => now()]);
        }
        // $calling->checkins;
        return redirect()->route('callings.index');
    }

    // confirmSSS
    public function confirmStore(Request $request)
    {
        
        $calling = Calling::find($request->calling_id);
        if($request->start == 1)
        $calling->personal_start_id = Auth::user()->tn;
        if($request->in_work == 1)
        $calling->personal_arrival_id = Auth::user()->tn;
        if($request->completed == 1)
        $calling->personal_end_id = Auth::user()->tn;
        $calling->save();
        if ($this->validateTimes($calling->arrival_time,  $calling->start_time, $calling->end_time)) {
             $calling->status = 'workshop-chief';
             $calling->save();
         }
        return redirect()->route('callings.index');
    }
    // confirmS
    public function create()
    {
        // find types where slug is Oplata-pratsi
      $publicInformation = $this->publicInformation();
         $user = Personal::where('tn',Auth::user()->tn)->first();
        if ($user) {
            // Получить список division_id для текущего пользователя
            $userDivisionIds = $user->divisions()->pluck('division_id');            
            // Найти всех сотрудников, которые принадлежат к этим же divisions
            $personnelInSameDivisions = Personal::whereHas('divisions', function ($query) use ($userDivisionIds) {
                $query->whereIn('division_id', $userDivisionIds);
            })
            ->with(['positions'])
            ->get()->keyBy('id');
            return view('callings.create', ['DI' => $publicInformation, 'personnelInSameDivisions'=>$personnelInSameDivisions]);
        } 
        return view('callings.create', ['DI' => $publicInformation]);
    }

    public function publicInformation(){
        foreach (Type::all() as $type)
        {
            $all_types[$type->id] = $type;  
        }
        $Oplata_pratsi_parent = Type::where('slug', 'Oplata-pratsi')->first();
        $Oplata_pratsi_ids = Type::where('parent_id', $Oplata_pratsi_parent->id)->get()->keyBy('id');
        $Vyklyk_na_robotu = Type::where('slug', 'Zaluchennya-personalu')->first();
        $Vyklyk_na_robotu_ids = Type::where('parent_id', $Vyklyk_na_robotu->id)->get()->keyBy('id');
        $works_type=Type::where('slug', 'Zaluchennya-personalu')->first();
        $works_types = Type::where('parent_id', $works_type->id)->get()->keyBy('id');
        $works_names = [];
        foreach($works_types as $work_type){
            $finish_types = Type::where('parent_id', $work_type->id)->get();
            foreach($finish_types as $finish_type){
                $works_names[$work_type->id][$finish_type->id]['name'] = $finish_type->name;
                $works_names[$work_type->id][$finish_type->id]['description'] = $finish_type->description;
            }
        }
        return ['Oplata_pratsi_ids' => $Oplata_pratsi_ids, 'Vyklyk_na_robotu_ids' => $Vyklyk_na_robotu_ids, 'works_names'=>$works_names, 'all_types'=>$all_types];
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $calling = Calling::find($id);
        $publicInformation = $this->publicInformation();
         $user = Personal::where('tn',Auth::user()->tn)->first();
         $personnelInSameDivisions = [];
        return view('callings.edit', ['calling' => $calling,  'DI' => $publicInformation, 'personnelInSameDivisions'=>$personnelInSameDivisions]);
     }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       // return $request;
        $calling = Calling::find($id);
        $filling=0;
        if($request->description){
            $calling->description = $request->description;
            $calling->save();
            $filling++;
        }
        if($request->arrival_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->arrival_time);
            $calling->arrival_time = $Time;
            $calling->save();
            $filling++;
        }
        if($request->start_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time);
            $start_time=$Time;
         $calling->start_time = $Time;
            $calling->save();
            $filling++;
        }else  $start_time=null;
    
        if($request->end_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time);
             $end_time=$Time;
            $calling->end_time =$Time;
            $calling->save();
            $filling++;
        }else  $end_time=null;
        if($request->Type_of_work){
            $calling->type_id = $request->Type_of_work;
            $calling->save();
            $filling++;
        }
        if($request->payments){
            $calling->workers()->detach();
            $Kerivnyk_bryhady = Type::where('slug', 'Kerivnyk-bryhady')->first();
            $Robitnyky = Type::where('slug', 'Robitnyky')->first();

            $filling++;
            foreach($request->payments as $worker_id => $payment_id){
                if(!$request->start_timew[$worker_id])
                        $start_timew=$start_time;
                else{
                    $start_timew= \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_timew[$worker_id]);
                    if($start_timew<$calling->start_time){
                        $calling->start_time=$start_timew;
                        $calling->save();
                    }
                    if($start_timew<$calling->arrival_time){
                        $calling->arrival_time=$start_timew;
                        $calling->save();
                    }
                }    
                if(!$request->end_timew[$worker_id])
                    $end_timew=$end_time;
                else{
                    $end_timew= \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_timew[$worker_id]);
                    if($end_timew>$calling->end_time){
                    $calling->end_time=$end_timew;
                    $calling->save();
                    }
                }
                if($request->chief==$worker_id){
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Kerivnyk_bryhady->id,
                     'payment_type_id' => $payment_id, 
                     'comment' => $request->comments[$worker_id],
                     'start_time' =>  $start_timew,
                        'end_time' => $end_timew]);
                }else{
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Robitnyky->id, 'payment_type_id' => $payment_id, 'comment' => $request->comments[$worker_id],
                        'start_time' =>  $start_timew,
                        'end_time' => $end_timew]);
                }
            }   
        }
        else{
            $calling->workers()->detach();
            $Kerivnyk_bryhady = Type::where('slug', 'Kerivnyk-bryhady')->first();
            $Robitnyky = Type::where('slug', 'Robitnyky')->first();
            foreach($request->comments as $worker_id => $comment){
                if($request->chief==$worker_id){
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Kerivnyk_bryhady->id, 'payment_type_id' => 9, 'comment' => $comment, 'start_time' =>  \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_timew[$worker_id]), 'end_time' => \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_timew[$worker_id])]);
                }else{
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Robitnyky->id, 'payment_type_id' => 9, 'comment' =>  $comment, 'start_time' => \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_timew[$worker_id]), 'end_time' =>  \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_timew[$worker_id])]);
                }
            }
        }
        if($this->validateTimes($calling->arrival_time,  $calling->start_time, $calling->end_time) && $filling==6) {
            $calling->status = 'supervision';
            $calling->save();
        }
       // return $this->validateTimes($calling->arrival_time,  $calling->start_time, $calling->end_time);
        return redirect()->route('callings.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $calling = Calling::find($id);
        if ($calling) {
            $calling->delete();
        }
        return redirect()->route('callings.index');
        
    }

    // print
    public function print(string $id)
    {
        $Oplata_pratsi_parent = Type::where('slug', 'Oplata-pratsi')->first();
        $Oplata_pratsi_ids = Type::where('parent_id', $Oplata_pratsi_parent->id)->get()->keyBy('id');
        $calling = Calling::find($id);
        return view('callings.print', ['calling' => $calling , 'Oplata_pratsi_ids' => $Oplata_pratsi_ids]);
    }

    // getPosibleDescriptions
    public function getPosibleDescriptions(Request $request)
    {
    //    body: JSON.stringify({ description })
    $words = explode(" ", $request->description);
    // find callings where description contains all words or one of them  orderby count of words
    $callings = Calling::where(function ($query) use ($words) {
        foreach ($words as $word) {
            $query->where('description', 'like', "%$word%");
        }
    })->orderBy('id', 'desc')
    // only description column
    ->select('description')
    ->get()
    // only 5 limit
    ->take(5);
    return response()->json($callings);
    }
}
