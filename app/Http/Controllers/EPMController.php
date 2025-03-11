<?php

namespace App\Http\Controllers;

use App\Models\EPM;
use App\Models\EPMdata;
use Illuminate\Http\Request;

class EPMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $epms = EPM::all();
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
            'area' => (int) $request->wanoarea
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
        $epm->area = $request->wanoarea;
        $epm->division = $request->division;
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

    /*// epmdata
    Route::get('/epmdata', 'App\Http\Controllers\EPMController@epmdata')->name('epmdata');
    Route::get('/epmdata/create', 'App\Http\Controllers\EPMController@createEpmData')->name('epmdata.create');
    Route::post('/epmdata', 'App\Http\Controllers\EPMController@storeEpmData')->name('epmdata.store');
    Route::get('/epmdata/{id}', 'App\Http\Controllers\EPMController@showEpmData')->name('epmdata.show');
    Route::get('/epmdata/{id}/edit', 'App\Http\Controllers\EPMController@editEpmData')->name('epmdata.edit');
    Route::put('/epmdata/{id}', 'App\Http\Controllers\EPMController@updateEpmData')->name('epmdata.update');
    Route::delete('/epmdata/{id}', 'App\Http\Controllers\EPMController@destroyEpmData')->name('epmdata.destroy');    
   */
    public function epmdata()
    {
        $epmdatas = EPMdata::all();
        $epmdata = [];
        foreach ($epmdatas as $epmd) {
           // return 
            $epmdata[$epmd->date_received] []=  $epmd;
        }
        //return  $epmdata;
        return view('epmdata.index', compact('epmdata'));
    }

    public function createEpmData()
    {
        return view('epmdata.create');
    }

    public function storeEpmData(Request $request)
    {
        $date=$request->date;
        $epms = EPM::all();
        foreach ($epms as $epm) {
            $epmdata = EPMdata::create([
                'epm_id' => $epm->id,
                'value' => null,
                'date_received' => $date,
                'date_entered' => null,
                'blocked' => 0,
                'user_id' => 1
            ]);
        }
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
        $division = Division::where('id',$request->division)->first(); 
        $epmdatas = EPMdata::where('date_received', $request->date)
        ->whereHas('epm', function ($query) use ($request) {
            $query->where('division', $request->division);
        })->get();
        return view('epmdata.load', compact('epmdatas','division'));
    }

}
