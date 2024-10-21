<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calling;
use App\Models\Personal;
use App\Models\Type;
use Illuminate\Console\View\Components\Warn;
use Illuminate\Support\Facades\Auth;

class CallingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       // Get the collection of started callings
       if($request->type){$type=$request->type;}
       if($request->start){$start=$request->start;}
       if($request->finish){$finish=$request->finish;}
       if(Auth::user()->hasRole('VONtaOP')){
            $callings = Calling::with(['workers.divisions'])->orderBy('id', 'asc')->get();
            return view('callings.VONtaOP', compact('callings'));
        }        
        elseif(Auth::user()->hasRole('Profkom')){
            $callings = Calling::with(['workers.divisions'])->orderBy('id', 'asc')->get();
            return view('callings.Profkom', compact('callings'));
        }        
        elseif(Auth::user()->hasRole('SVNtaPB')){
            $callings = Calling::with(['workers.divisions'])->orderBy('id', 'asc')->get();
            return view('callings.SVNtaPB', compact('callings'));
        }
        elseif(Auth::user()->hasRole('workshop-chief')){
            $callings = Calling::with(['workers.divisions'])
            ->where('start_time', '!=', null)
            ->where('personal_arrival_id', '!=', null)
            ->where('arrival_time', '!=', null)
            ->where('personal_start_id', '!=', null)
            ->where('end_time', '!=', null)
            ->where('personal_end_id', '!=', null)
            ->orderBy('id', 'asc')->get();
            return view('callings.workshop_chief', compact('callings'));
        }        
        elseif(Auth::user()->hasRole('supervision')){

            $callings = Calling::with(['workers.divisions'])
           // ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 day')))
             ->orderBy('id', 'asc')->get();
            return view('callings.supervision', compact('callings'));
        }
        elseif( Auth::user()->hasRole('user')){
              $callings = Calling::with(['workers.divisions'])->orderBy('id', 'asc')->get();
                 return view('callings.index', compact('callings'));
        }
        $callings = Calling::with(['workers.divisions'])->orderBy('id', 'asc')->get();
      return view('callings.index', compact('callings'));
  
    }

   
    public function store(Request $request)
    {
        $Oplata_pratsi = $request->payments;
        
  
        $calling = new Calling();
        if($request->description){
            $calling->description = $request->description;

            $calling->save();
        }
       
        if($request->arrival_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->arrival_time);
               $calling->arrival_time = $Time;

            $calling->save();
        }
         if($request->start_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time);
         $calling->start_time = $Time;
            $calling->save();
        }
        if($request->work_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->work_time);
            $calling->work_time = $Time;
            $calling->save();
        }   
        if($request->end_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time);
            $calling->end_time =$Time;
            $calling->save();
        }
        if($request->Type_of_work){
            $calling->type_id = $request->Type_of_work;
            $calling->save();
        }
        if($request->workers){
            // find type where slug is Kerivnyk-bryhady
            $Kerivnyk_bryhady = Type::where('slug', 'Kerivnyk-bryhady')->first();
            $Robitnyky = Type::where('slug', 'Robitnyky')->first();
            
            foreach($request->workers as $worker){
                if($request->chief==$worker){
                    $calling->workers()->attach($worker, ['worker_type_id' => $Kerivnyk_bryhady->id, 'payment_type_id' => $Oplata_pratsi[$worker], 'comment' => $request->comments[$worker]]);
                }else{
                    $calling->workers()->attach($worker, ['worker_type_id' => $Robitnyky->id, 'payment_type_id' => $Oplata_pratsi[$worker], 'comment' => $request->comments[$worker]]);
                }
            }
        }
        return redirect()->route('callings.index');
    }

    // getPersonalForTN
    public function getPersonalForTN(Request $request)
    {
        $tn = $request->tn;
        $personal = Personal::where('tn', $tn)->first();
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
        //
        $calling = Calling::find($request->calling_id);     
        if($calling){
            $calling->checkins()->attach(Auth::user()->id, [
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
        //return $request;
        //
        $calling = Calling::find($request->calling_id);     
        if($calling){
            $calling->checkins()->attach(Auth::user()->id, ['checkin_type_id' => $request->checkin_type_id, 'type' => 0, 'comment' => $request->comment,
            'created_at' => now(), 'updated_at' => now()]);
        }
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
            })->get();
            return view('callings.create', ['DI' => $publicInformation, 'personnelInSameDivisions'=>$personnelInSameDivisions]);
        } 
        return view('callings.create', ['DI' => $publicInformation]);
    }

    public function publicInformation(){
        $all_types = Type::all();
        $Oplata_pratsi_parent = Type::where('slug', 'Oplata-pratsi')->first();
        $Oplata_pratsi_ids = Type::where('parent_id', $Oplata_pratsi_parent->id)->get();
        $Vyklyk_na_robotu = Type::where('slug', 'Zaluchennya-personalu')->first();
        $Vyklyk_na_robotu_ids = Type::where('parent_id', $Vyklyk_na_robotu->id)->get();
        $works_type=Type::where('slug', 'Zaluchennya-personalu')->first();
        $works_types = Type::where('parent_id', $works_type->id)->get();
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
        if($request->description){
            $calling->description = $request->description;
            $calling->save();
        }
        if($request->arrival_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->arrival_time);
            $calling->arrival_time = $Time;
            $calling->save();
        }
        if($request->start_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->start_time);
            $calling->start_time = $Time;
            $calling->save();
        }
        if($request->end_time){
            $Time = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $request->end_time);
            $calling->end_time = $Time;
            $calling->save();
        }
        if($request->Type_of_work){
            $calling->type_id = $request->Type_of_work;
            $calling->save();
        }
        if($request->payments){
            $calling->workers()->detach();
            $Kerivnyk_bryhady = Type::where('slug', 'Kerivnyk-bryhady')->first();
            $Robitnyky = Type::where('slug', 'Robitnyky')->first();
            foreach($request->payments as $worker_id => $payment_id){
                if($request->chief==$worker_id){
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Kerivnyk_bryhady->id, 'payment_type_id' => $payment_id, 'comment' => $request->comments[$worker_id]]);
                }else{
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Robitnyky->id, 'payment_type_id' => $payment_id, 'comment' => $request->comments[$worker_id]]);
                }
            }   
        }
        else{
            $calling->workers()->detach();
            $Kerivnyk_bryhady = Type::where('slug', 'Kerivnyk-bryhady')->first();
            $Robitnyky = Type::where('slug', 'Robitnyky')->first();
            foreach($request->comments as $worker_id => $comment){
                if($request->chief==$worker_id){
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Kerivnyk_bryhady->id, 'payment_type_id' => 9, 'comment' => $comment]);
                }else{
                    $calling->workers()->attach($worker_id, ['worker_type_id' => $Robitnyky->id, 'payment_type_id' => 9, 'comment' =>  $comment] );
                }
            }

        }
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
        $Oplata_pratsi_ids = Type::where('parent_id', $Oplata_pratsi_parent->id)->get();
        $calling = Calling::find($id);
        return view('callings.print', ['calling' => $calling , 'Oplata_pratsi_ids' => $Oplata_pratsi_ids]);
    }
}
