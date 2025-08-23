<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Type;
use App\Models\CarOrder;
// personal
use App\Models\Personal;
use Illuminate\Support\Facades\Auth;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $cars = Car::orderBy('id', 'asc')->get();
        $allTypes = Type::all()->keyBy('id')->values();
       // 'name', 'type_id', 'gov_number', 'condition_id', 'driver_personal_id'
        return view('cars.index', compact('cars' , 'allTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $all_types = Type::orderBy('id', 'asc')->get();
      return   view('cars.create', compact('all_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $car = new Car();
        $car->name = $request->name;
        $car->type_id = $request->type_id;
        $car->gov_number = $request->gov_number;
        $car->condition_id = $request->condition_id;
        $car->save();
        return redirect()->route('cars.index');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $car = Car::find($id);
        return view('cars.show', compact('car'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $car = Car::find($id);
        $all_types = Type::orderBy('id', 'asc')->get()->keyBy('id')->values();
        return view('cars.edit', compact('car', 'all_types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $car = Car::find($id);
        $car->name = $request->name;
        $car->type_id = $request->type_id;
        $car->gov_number = $request->gov_number;
        $car->condition_id = $request->condition_id;
        $car->save();
        return redirect()->route('cars.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $car = Car::find($id);
        $car->delete();
        return redirect()->route('cars.index');

    }

    ////////////////////////////////////////////////////////////////////////////
    /*
    
    Route::get('/carorders', 'App\Http\Controllers\CarController@carorders')->name('carorders');
    Route::get('/carorders/create', 'App\Http\Controllers\CarController@createCarOrder')->name('carorders.create');
    Route::post('/carorders', 'App\Http\Controllers\CarController@storeCarOrder')->name('carorders.store');
    Route::get('/carorders/{id}', 'App\Http\Controllers\CarController@showCarOrder')->name('carorders.show');
    Route::get('/carorders/{id}/edit', 'App\Http\Controllers\CarController@editCarOrder')->name('carorders.edit');
    Route::put('/carorders/{id}', 'App\Http\Controllers\CarController@updateCarOrder')->name('carorders.update');
    Route::delete('/carorders/{id}', 'App\Http\Controllers\CarController@destroyCarOrder')->name('carorders.destroy');
     */
    public function carorders()
    {
        //
       $all_types = Type::orderBy('id', 'asc')->get()->keyBy('id')->values();
        $carorders = CarOrder::orderBy('id', 'asc')->get();
        return view('carorders.index', compact('carorders', 'all_types'));
    }

    public function createCarOrder()
    {
        //
        $all_types = Type::orderBy('id', 'asc')->get()->keyBy('id')->values();
        return view('carorders.create', compact('all_types'));
    }

    public function storeCarOrder(Request $request)
    {
        $carorder = new CarOrder();
        $carorder->title = $request->title;
        $carorder->description = $request->description;
        $carorder->typecar_id = $request->type_id; // car type
        $carorder->val = $request->value; // how mach 
        $carorder->value_type_id = $request->value_type_id; // what type of value
        $carorder->status = $request->status; // Normal, Urgent, Emergency
        $carorder->start_datetime = $request->start_datetime;
        $carorder->end_datetime = $request->end_datetime;
        $carorder->division_id = Auth::user()->personal->divisions[0]->id;
        $carorder->hours = $request->hours;
        $carorder->start_point = $request->start_point;
        $carorder->end_point = $request->end_point;
        $carorder->save();
       // return $carorder;
        return redirect()->route('carorders');
    }

    public function showCarOrder(string $id)
    {
        //
        $carorder = CarOrder::find($id);
        return view('carorders.show', compact('carorder'));
    }

    public function editCarOrder(string $id)
    {
        //
        $carorder = CarOrder::find($id);
        $all_types = Type::orderBy('id', 'asc')->get()->keyBy('id')->values();
        
        return view('carorders.edit', compact('carorder', 'all_types'));
    }

    public function updateCarOrder(Request $request, string $id)
    {
        //
        $carorder = CarOrder::find($id);
        $carorder->title = $request->title;
        $carorder->description = $request->description;
        $carorder->typecar_id = $request->type_id; // car type
        $carorder->val = $request->value; // how mach 
        $carorder->value_type_id = $request->value_type_id; // what type of value
        $carorder->status = $request->status; // Normal, Urgent, Emergency
        $carorder->start_datetime = $request->start_datetime;
        $carorder->end_datetime = $request->end_datetime;
        $carorder->division_id = Auth::user()->personal->divisions[0]->id;
        $carorder->hours = $request->hours;
        $carorder->start_point = $request->start_point;
        $carorder->end_point = $request->end_point;

        $carorder->save();
        return redirect()->route('carorders');
    }

    public function destroyCarOrder(string $id)
    {
        //
        $carorder = CarOrder::find($id);
        $carorder->delete();
        return redirect()->route('carorders');
    }


    //////////////////////////////////////////

    public function plancars()
    {
        // find personal hwo is driver find positions has name word driver
         $drivers = Personal::orderBy('id', 'asc')->with('positions')
        ->whereHas('positions', function($q){
            $q->where('name', 'like', '%водій%');
            // not автобуса або легкового
            $q->where('name', 'not like', '%автобуса%');
            $q->where('name', 'not like', '%легкового%');
        })
        ->get();
        $cars = Car::orderBy('type_id', 'asc')->get();
        $allTypes = Type::orderBy('id', 'asc')->get()->keyBy('id')->values();
        $carorders = CarOrder::orderBy('id', 'asc')->get();
        $date=date('Y-m-d');
       // 'name', 'type_id', 'gov_number', 'condition_id', 'driver_personal_id'
        return view('cars.plancars', compact('cars' , 'allTypes', 'drivers', 'date', 'carorders'));
    }
    // assignDriver post
    public function assignDriver(Request $request)
    {
        $car = Car::find($request->car_id);
        // assign driver
        $car->drivers()->attach($request->driver_id, ['start_date' => $request->start_date, 'end_date' => $request->end_date]);
        return response()->json([
            'success' => true,
            'car_id' => $car->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
    }


}
