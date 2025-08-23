<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;

use App\Models\Division;
use App\Models\Room;
use App\Helpers\FileHelpers;
use App\Helpers\StringHelpers; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;


class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public   $stat_data = [
        'address' => '',
        'city' => 'Нетішин',
        'state' => 'Хмельницька область',
        'zip' => '30100',
        'country' => 'Україна',
        'organization' => 'Хмельницька АЕС',
        'status' => '1',
        'image' => ''
    ];
    public function index()
    {
        //
        $buildings = Building::orderBy('name', 'asc')->get();
        return view('buildings.index', compact('buildings'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('buildings.create');
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $building = new Building([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => $request->country,
            'organization' => $request->organization,
            'status' => $request->status,
            'image' => $request->image
        ]);
        $building->save();
        return redirect()->route('buildings.index');
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
    
    public function import()
    {
        return view('buildings.import');
    }
    public function importData(Request $request)
    {
      
        set_time_limit(0);
        
        $type_of_file = $request->type_of_file ?? 0;
        $csvData = FileHelpers::csvToArray($request->file('file'), $type_of_file);
        $type_id = $request->type_id ?? 0;

       // DB::beginTransaction();
       
        try {
            foreach ($csvData as $dt) {
                $data = str_getcsv($dt, ";");
               // return $data;
// 0	1ID_ROOM	2ID_BUILDING	3Будівля	4NAME_BUILDING	5Номер приміщення	6Назва приміщення	7Відмітка	8Площа	9Кат.ПБ	10ЗСР	11Підрозділ-власник	12Дільниця

                if ($data[1] !== 'ID_ROOM') {
                    $building = Building::firstOrCreate(
                        ['IDBuilding' => $data[2]],
                        [
                            'IDBuilding' => $data[2],
                            'name' => $data[4],
                            'address' => $this->stat_data['address'],
                            'city' => $this->stat_data['city'],
                            'state' => $this->stat_data['state'],
                            'zip' => $this->stat_data['zip'],
                            'country' => $this->stat_data['country'],
                            'abv' => $data[3],
                            'slug' => StringHelpers::generateSlug($data[4]),
                            'organization' => $this->stat_data['organization'],
                            'status' => $this->stat_data['status'],
                            'image' => $this->stat_data['image']
                        ]
                    );
                    
                    /*lockForUpdate()->find($data[2]);
                   // return $data[0];
                    if (!$building) {
                        // Создание нового здания
                        $building = new Building([
                            'id' => $data[2],
                            'IDBuilding' => $data[2],
                            'name' => $data[4],
                            'address' => $this->stat_data['address'],
                            'city' => $this->stat_data['city'],
                            'state' => $this->stat_data['state'],
                            'zip' => $this->stat_data['zip'],
                            'country' => $this->stat_data['country'],
                            'abv' => $data[3],
                            'slug' => StringHelpers::generateSlug($data[4]),
                            'organization' => $this->stat_data['organization'],
                            'status' => $this->stat_data['status'],
                            'image' => $this->stat_data['image']
                        ]);
                        $building->save();
                       
                    }*/
//return $building;
                    $owner_division = null;
                    $owner_subdivision = null;
//return $data[11];
                    if (!empty($data[11])) {
                        $division = Division::firstOrCreate(
                            ['name' => $data[11]],
                            [
                                'description' => $data[11],
                                'abv' => $data[11],
                                'slug' => StringHelpers::generateSlug($data[11]),
                                'parent_id' => 0
                            ]
                        );
                        $division->name = $data[11];
                        $division->description = $data[11];
                        $division->abv = $data[11];
                        $division->slug = StringHelpers::generateSlug($data[11]);
                        $division->parent_id = 0;
                        $division->save();

                        $owner_division = $division->id;
                       // return $division;

                        if (!empty($data[12])) {
                            $subdivision = Division::firstOrCreate(
                                ['name' => $data[12]],
                                [
                                    'description' => $data[12],
                                    'abv' => $data[12],
                                    'slug' => StringHelpers::generateSlug($data[12]),
                                    'parent_id' => $division->id
                                ]
                            );
                            $subdivision->name = $data[12];
                            $subdivision->description = $data[12];
                            $subdivision->abv = $data[12];
                            $subdivision->slug = StringHelpers::generateSlug($data[12]);
                            $subdivision->parent_id = $division->id;
                            $subdivision->save();

                            $owner_subdivision = $subdivision->id;
                        }
                    }

                    $room = Room::lockForUpdate()->find($data[1]);
                    $RadiationSafetyZone=0;
                    if (!$room) {
                        // Создание новой комнаты
                        //Undefined array key 10
                        if ( isset($data[10]) && $data[10] !== 'так' && $data[10] !== 'ні')
                          {  $RadiationSafetyZone=1;}

                        $room = new Room([
                            'id' => $data[1],
                            'IDname' => $data[1] ?? '',
                            'name' => $data[5] ?? '',
                            'description' => $data[6] ?? '',
                            'square' => $data[8] ?? 0,
                            'floor' => $data[7] ?? 0,
                            'building_id' => $building->id,
                            'category_PB' => $data[9] ?? '',
                            'RadiationSafetyZone' => $RadiationSafetyZone,
                            'owner_division' => $owner_division,
                            'owner_subdivision' => $owner_subdivision
                        ]);
                        $room->save();
                    }
                $building->rooms()->syncWithoutDetaching([$room->id]);
                   
                }
            }

            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error("Error in importData: " . $e->getMessage());
            Log::error("SQL: " . $e->getSql());
            Log::error("Bindings: " . json_encode($e->getBindings()));
            return redirect()->route('buildings.index')->withErrors(['msg' => 'Error during import']);
        }
$rooms = Room::all()->keyBy('id')->values();
        return $rooms;
       // return redirect()->route('buildings.index')->with('success', 'Data imported successfully');
    }
}

