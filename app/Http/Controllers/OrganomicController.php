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
       
        $buildings = Building::orderBy('id')->get();

        return  view('organomics.index', ['buildings' => $buildings]);
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
    public function show(string $id )
    {
         // Отримання кімнат для будівлі з ID = 1
         $rooms = Building::find($id)->rooms()->get();
         $effectiveness = [];
         $minSpacePerPerson = 3; // Мінімальна площа на одну людину для комфортної роботи
     
         foreach ($rooms as $room) {
             $personal_count = intval($room->personals()->count()); // Перетворення на ціле число
             $square = floatval($room->square); // Перетворення на число з плаваючою точкою
     
             // Визначення статусу пожежної безпеки
             $pb_category = $room->category_PB;
             $fireSafetyStatus = match ($pb_category) {
                 'А' => 'Висока пожежна небезпека',
                 'Б' => 'Підвищена пожежна небезпека',
                 'В' => 'Середня пожежна небезпека',
                 'Г' => 'Низька пожежна небезпека',
                 'Д' => 'Безпечно',
                 default => 'Невизначено',
             };
     
             // Перевірка комфортності та статусу персоналу
             if ($square > 0 && $personal_count > 0) {
                 $spacePerPerson = $square / $personal_count; // Кількість метрів на одну людину
                 $efficiency_value = ($personal_count * $minSpacePerPerson) / $square * 100;
     
                 // Визначення комфортності з урахуванням пожежної небезпеки
                 $comfortStatus = $spacePerPerson >= $minSpacePerPerson && $fireSafetyStatus === 'Безпечно' 
                     ? 'Комфортно' 
                     : 'Не комфортно';
     
                 $effectiveness[] = [
                     'room_id' => $room->id,
                     'name' => $room->name,
                     'description' => $room->description,
                     'floor' => $room->floor,
                     'pb_category' => $pb_category,
                     'radiation_safety_zone' => $room->RadiationSafetyZone,
                     'personal_count' => $personal_count,
                     'square' => $square,
                     'effectiveness' => min($efficiency_value, 100), // Обмеження ефективності до 100%
                     'space_per_person' => $spacePerPerson, // Площа на одну людину
                     'comfort_status' => $comfortStatus, // Статус комфортності
                     'fire_safety_status' => $fireSafetyStatus // Статус пожежної небезпеки
                 ];
             } elseif ($personal_count == 0) {
                 // Якщо немає персоналу, ефективність = 0
                 $effectiveness[] = [
                     'room_id' => $room->id,                    
                     'name' => $room->name,
                     'description' => $room->description,
                     'floor' => $room->floor,
                     'pb_category' => $pb_category,
                     'radiation_safety_zone' => $room->RadiationSafetyZone,
                     'personal_count' => $personal_count,
                     'square' => $square,
                     'effectiveness' => 0,
                     'space_per_person' => null,
                     'comfort_status' => 'Порожньо',
                     'fire_safety_status' => $fireSafetyStatus
                 ];
             } else {
                 // Якщо площа дорівнює нулю, ефективність = 0
                 $effectiveness[] = [
                     'room_id' => $room->id,                    
                     'name' => $room->name,
                     'description' => $room->description,
                     'floor' => $room->floor,
                     'pb_category' => $pb_category,
                     'radiation_safety_zone' => $room->RadiationSafetyZone,
                     'personal_count' => $personal_count,
                     'square' => $square,
                     'effectiveness' => 0,
                     'space_per_person' => null,
                     'comfort_status' => 'Недоступно',
                     'fire_safety_status' => $fireSafetyStatus
                 ];
             }
         }
         // сортировать по этажам
         usort($effectiveness, function ($a, $b) {
             return $a['floor'] <=> $b['floor'];
         });
            return  view('organomics.show', ['effectiveness' => $effectiveness]);
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
