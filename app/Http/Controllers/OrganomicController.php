<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// building
use App\Models\Building;
use App\Models\Division;
use App\Models\Room;
// personal
use App\Models\Personal;
use App\Models\Position;
// phone
use App\Models\Phone;

class OrganomicController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
  
       $buildings = Building::orderBy('id', 'asc')
       ->limit(100)
       ->get();
       foreach ($buildings as $building) {
        $rooms = [
            'building_id' => $building->id,
            'rooms' => $building->rooms()->get()
        ] ; 
       }
      
       return $rooms;
        return view('organomics.index', compact('buildings'));
    }

    public function effectivness($building_id, $division_id)
    {
        $division = Division::find($division_id);
        $subdivisions = Division::where('parent_id', $division_id)->get();
        $personal = Personal::where('division_id', $division_id)->get();
        // найти все комнаты в здании и считаем количество рабочих мест и  площадь которую они занимают 
        $rooms = Room::where('building_id', $building_id)->get();

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('organomics.create');
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
