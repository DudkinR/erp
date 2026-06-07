<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Type;
use App\Models\Objct; 
use App\Models\Taxiroute;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class TaxiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('trc_manager'))     {
            $now = Carbon::now();
            $halfHourLater = Carbon::now()->addMinutes(30);

            $routes = Taxiroute::with(['car', 'passengers', 'from', 'to'])
                ->whereDate('date', Carbon::today()) // тільки сьогоднішня дата
                ->where('time', '>=', $now->format('H:i')) // час більший або рівний зараз
                ->get();
            
            $cars = Car::with('type', 'condition')->get();
            $Mashyny_id = Type::where('slug', 'Mashyny')->first()->id;
            $objects = Objct::all();
            $types = Type::where('parent_id', $Mashyny_id )->get();
            $Condition_id = Type::where('slug', 'conditions')->first()->id;
            $conditions = Type::where('parent_id', $Condition_id )->get();
            $divisions = Division::all();
            $users = User::all();
            return view('taxi.indexmanager', compact('cars', 'types', 'conditions', 'objects', 'routes', 'divisions', 'users'));
        }
        else
          return view('taxi.index');
        

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    public function storeObject(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
        ]);
       Objct::create($validatedData);
        return redirect()->route('taxi.index')->with('success', 'Object created successfully.');
    }
     public function createcar()
    {
        //
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('trc_manager'))     {
            
            return view('taxi.createcar'); 
        }
        else
          return view('taxi.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        
    }
    public function storecar(Request $request)
    {
        // {"_token":"FqoXgUTrE7jMwrYB5Wax3Xx5oZxuBIIZT9lPq5G1","name":"saa","gov_number":"adas","seats":"6","features":"sa","type_id":"33","condition_id":"37"}
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'gov_number' => 'required|string|max:255',
            'seats' => 'required|integer',
            'features' => 'nullable|string',
            'type_id' => 'required|exists:types,id',
            'condition_id' => 'required|exists:types,id',
        ]);
        Car::create($validatedData);
        return redirect()->route('taxi.index')->with('success', 'Car created successfully.');
    }

    //storeRoute
    public function storeRoute(Request $request)
    {
        $validatedData = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'from_id' => 'required|exists:objects,id',
            'to_id' => 'required|exists:objects,id',
            'date' => 'required|date',
            'time' => 'required',
            
        ]);
        Taxiroute::create($validatedData);
        return redirect()->route('taxi.index')->with('success', 'Route created successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    // duty
public function duty(Request $request)
{
    $validated = $request->validate([
        'car_ids' => 'required|array',
        'car_ids.*' => 'exists:cars,id',
        'condition_ids' => 'required|array',
        'condition_ids.*' => 'exists:types,id',
    ]);

    foreach ($validated['car_ids'] as $index => $carId) {
        $car = Car::findOrFail($carId);
        $car->condition_id = $validated['condition_ids'][$index];
        $car->save();
    }

    return redirect()->route('taxi.index')->with('success', 'Машини оновлено.');
}




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }
    public function editcar(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
