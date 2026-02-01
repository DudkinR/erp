<?php

namespace App\Http\Controllers;

use App\Models\EPM;
use App\Models\EPMdata;
use App\Models\Division;
use App\Models\WANOAREA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EPMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $epms = EPM::all()->keyBy('id')->values();
        return view('epms.index', compact('epms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('epms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $epm = EPM::create([
            'name' => (string) $request->name,
            'description' => (string) $request->description, 
            'division' => $request->division ?? 0, // Додаємо значення за замовчуванням   
            'area' => (int) $request->wanoarea?? 0,
            'min' => (int) $request->min,
            'max' => (int) $request->max,
        ]);
        
        
        
        return redirect('/epm')->with('success', 'epmloyee saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $epm = EPM::find($id);
        return view('epms.show', compact('epm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $epm = EPM::find($id);
        return view('epms.edit', compact('epm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $epm =  EPM::find($id);
        $epm->name = $request->name;
        $epm->description = $request->description;
        $epm->area = $request->wanoarea ?? 0;
        $epm->division = $request->division;
        $epm->min = $request->min;
        $epm->max = $request->max;
        $epm->save();
        return redirect('/epm')->with('success', 'epmloyee updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {        //
        $epm = EPM::find($id);
        $epm->delete();
        return redirect('/epm')->with('success', 'epmloyee deleted!');
    }

    public function epmdata()
    {
        // Завантажуємо всі EPM у пам'ять (щоб не робити find() у циклі)
        $epms = EPM::all()->keyBy('id');
        // Отримуємо всі записи з ненульовою датою одразу
        $records = EPMdata::whereNotNull('date_received')
            ->with('epm') // жадне завантаження зв'язку
            ->get();

        // Групуємо записи по датах
        $groupedByDate = $records->groupBy('date_received');

        $epmdata_by_date = [];

        foreach ($groupedByDate as $date => $epmdata_records) {
            $completed = 1;
            $blocked = 1;
            $divisions_data = [];
            $epm_id =$epmdata_records[0]->epm_id;
            foreach ($epmdata_records as $record) {
                if (is_null($record->value)) {
                    $completed = 0;
                }

                if ($record->blocked == 0) {
                    $blocked = 0;
                }

                $division_id = $record->epm ? $record->epm->division : null;
                $division_key = $division_id ?? 'no_division';

                if (!isset($divisions_data[$division_key])) {
                    $divisions_data[$division_key] = [
                        'empty' => 0,
                        'total' => 0,
                    ];
                }

                $divisions_data[$division_key]['total']++;
                if (is_null($record->value)) {
                    $divisions_data[$division_key]['empty']++;
                }
            }
            $epmdata_by_date[$date] = [
                'completed' => $completed,
                'blocked' => $blocked,
                'divisions' => $divisions_data,
                'epm_id' => $epm_id
            ];
        }

        return view('epmdata.index', compact('epmdata_by_date', 'epms'));
    }
    // bloked
    public function bloked(Request $request)
    {
        $date = $request->date;
        $epmdata = EPMdata::where('date_received', $date)->get();
        foreach ($epmdata as $epm) {
            $epm->blocked = 1;
            $epm->save();
        }
        // redirect to back
        return redirect()->back()->with('success', 'epmdata blocked!');
    }
    // download CSV
    public function download(Request $request)
    {
        $date = $request->date;
        $epmdata = EPMdata::where('date_received', $date)->get();
        
        $filename = storage_path('app/epmdata.csv');
        $handle = fopen($filename, 'w+');
    
        // Додаємо BOM для коректного відкриття в Excel
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
    
        fputcsv($handle, ['Назва', 'area','value'], ';');
    
        foreach ($epmdata as $epmd) {
            $epm = EPM::find($epmd->epm_id);
            $area = WANOAREA::find($epm->area);

            $epm_name = $epm ? $epm->name : 'Unknown';
            $area_name = $area ? $area->name : 'Unknown';
            $value = $epmd->value;
    
            fputcsv($handle, [$epm_name, $area_name, $value], ';');
        }
    
        fclose($handle);
    
        return response()->download($filename, 'epmdata.csv', [
            'Content-Type' => 'text/csv',
        ])->deleteFileAfterSend(true);
    }
    // info
    public function info()
    {
        $epmdata = EPMdata::orderBy('date_received')->get();
        $epms = EPM::all()->keyBy('id')->values();
        $areas = WANOAREA::all()->keyBy('id')->values();
    
        $area_data = [];
        $area_title = [];
        foreach ($epmdata as $epmd) {
            $epm = $epms[$epmd->epm_id] ?? null;
            if (!$epm) continue;
    
            $area = $areas[$epm->area] ?? null;
            if (!$area) continue;
    
            $area_name = $area->name;
            $area_data[$area_name]['values'][] = $epmd->value;
            $area_data[$area_name]['dates'][] = $epmd->date_received;
            $area_title[$area_name] = $areas[$epm->area]->description;
        }
    
        return view('epmdata.info', compact('area_data', 'area_title'));
    }
    
    
   

    public function createEpmData()
    {

        return view('epmdata.create');
    }

    public function storeEpmData(Request $request)
    {
      $date=$request->date;
      // return Auth::id();

      $epm = EPM::find($request->epm_id);
        // if date is not set
        if(EPMdata::where('date_received', $date)->exists()){
            return redirect('/epmdata')->with('error', 'epmdata already exists!');
        }        
        $epmdata = EPMdata::create([
                'epm_id' => $epm->id,
                'value' => $request->data,
                'date_received' => $date,
                'date_entered' => $date,
                'blocked' => 0,
                'user_id' => Auth::id() 
            ]);
        
        return redirect('/epmdata')->with('success', 'epmdata saved!');
    }

    public function showEpmData($id)
    {
        $epmdata = EPMdata::find($id);
        return view('epmdata.show', compact('epmdata'));
    }

    public function editEpmData($id)
    {
        $epmdata = EPMdata::find($id);
        return view('epmdata.edit', compact('epmdata'));
    }

    public function updateEpmData(Request $request, $id)
    {
        $epmdata =  EPMdata::find($id);
        $epmdata->epm_id = $request->epm_id;
        $epmdata->value = $request->value;
        $epmdata->date_received = $request->date_received;
        $epmdata->date_entered = $request->date_entered;
        $epmdata->blocked = $request->blocked;
        $epmdata->user_id = $request->user_id;
        $epmdata->save();
        return redirect('/epmdata')->with('success', 'epmdata updated!');
    }

    public function destroyEpmData($id)
    {
        $epmdata = EPMdata::find($id);
        $epmdata->delete();
        return redirect('/epmdata')->with('success', 'epmdata deleted!');
    }
    //load get with data and division
    public function load(Request $request)
    {
        $date= date('Y-m-d', strtotime($request->date));
        $divvision_id= $request->division;
        if($divvision_id !== 'no_division'){
        $division = Division::where('id',$request->division)->first(); 
         $epmdatas = EPMdata::where('date_received', $date)
        ->whereHas('epm', function ($query) use ($divvision_id) { 
            $query->where('division', $divvision_id);
                    })
        ->get();
        }else{
            $division = null;
            $epmdatas = EPMdata::where('date_received', $date)            
            ->whereHas('epm', function ($query)  { 
             $query->whereNull('division');
            })
            ->get();
        }
          $epm = EPM::find($request->epm_id);
        
      // return   $request;
        return view('epmdata.newdata', compact('epmdatas','division','date','epm'));
    }
    //loadupdate
    public function loadupdate(Request $request)
    {
        //return $request;
         $date= date('Y-m-d', strtotime($request->date));
        foreach ($request->value as $key => $value) {
            $epmdata = EPMdata::find($key);
            if($epmdata->blocked == 1){
                continue;
            }
            if($epmdata->date_received != $date){
                continue;
            }    
            if($value == null){
                continue;
            }
            $epmdata->value = $value;
            $epmdata->user_id = Auth::user()->id;
            $epmdata->save();
        }
       
        return redirect('/epmdata')->with('success', 'epmdata updated!');
    }
    // Route::get('/epmdata/{date}', 'App\Http\Controllers\EPMController@showEpmData')->name('epmdata.show');
   
    public function showEpmDataByDate($date)
    {
        $epmdata = EPMdata::where('date_received', $date)->get();
        return view('epmdata.show', compact('epmdata'));
    }

}
