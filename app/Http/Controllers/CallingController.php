<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calling;
use App\Models\Personal;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;

class CallingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
       // Get the collection of started callings
       $callings = Calling::with(['workers.divisions'])->orderBy('id', 'asc')->get();
       return $callings;
       
       
      $unfinishedWork = Calling::where('end_time', null)->get();
      $notFinalizedWork = Calling::where('end_time', '!=', null)->get();
      $finishedWork = Calling::where('end_time', true)->get();
  
      return view('callings.index', compact('unfinishedWork', 'notFinalizedWork', 'finishedWork', 'callings'));
  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // find types where slug is Oplata-pratsi
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
         $user = Personal::where('tn',Auth::user()->tn)->first();
        if ($user) {
            // Получить список division_id для текущего пользователя
            $userDivisionIds = $user->divisions()->pluck('division_id');            
            // Найти всех сотрудников, которые принадлежат к этим же divisions
            $personnelInSameDivisions = Personal::whereHas('divisions', function ($query) use ($userDivisionIds) {
                $query->whereIn('division_id', $userDivisionIds);
            })->get();
            return view('callings.create', ['Oplata_pratsi_ids' => $Oplata_pratsi_ids, 'Vyklyk_na_robotu_ids' => $Vyklyk_na_robotu_ids, 'personnelInSameDivisions'=>$personnelInSameDivisions, 'works_names'=>$works_names, 'all_types'=>$all_types]);
        } 
        return view('callings.create', ['Oplata_pratsi_ids' => $Oplata_pratsi_ids, 'Vyklyk_na_robotu_ids' => $Vyklyk_na_robotu_ids, 'works_names'=>$works_names, 'all_types'=>$all_types]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Oplata_pratsi = $request->payments;
        
    /*    $Oplata_pratsi = Type::where('slug', 'Oplata-pratsi')->first();
        $Oplata_pratsi_id = $Oplata_pratsi->id;
        'description',
        'type_id',
        'start_time',
        'personal_start_id',
        'arrival_time',
        'personal_arrival_id',
        'work_time',
        'personal_work_id',
        'end_time',
        'personal_end_id',
*/
        $calling = new Calling();
        if($request->description){
            $calling->description = $request->description;
            $calling->type_id = $request->Type_of_work;
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
        if($request->vyklyk_na_robotu){
            $calling->type_id = $request->type;
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
        return view('callings.show', ['calling' => $calling]);
    }
    // confirmSS
    public function confirmSS(string $id)
    {
        //
        $calling = Calling::find($id);     
        return view ('callings.confirmSS', ['calling' => $calling]);
        //redirect()->route('callings.confirmSS', ['calling' => $calling]);
    }
    // confirmSSS
    public function confirmStore(Request $request, string $id)
    {
        //
        $calling = Calling::find($id);
        $calling->save();
        return redirect()->route('callings.index');
    }
    // confirmS

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $Vyklyk_na_robotu_parent = Type::where('slug', 'Vyklyk-na-robotu')->first();
        $Vyklyk_na_robotu_ids = Type::where('parent_id', $Vyklyk_na_robotu_parent->id)->get();
        $calling = Calling::find($id);
        $Oplata_pratsi = Type::where('slug', 'Oplata-pratsi')->first();
        $Oplata_pratsi_ids = Type::where('parent_id', $Oplata_pratsi->id)->get();
        return view('callings.edit', ['calling' => $calling , 'Vyklyk_na_robotu_ids' => $Vyklyk_na_robotu_ids, 'Oplata_pratsi_ids' => $Oplata_pratsi_ids]);
       }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $description = $request->input('description');
        $calling = Calling::find($id);
        $calling->description = $description;
        $calling->save();
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
}
