<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Division;
use App\Models\Personal;

class PositionController extends Controller
{
    // index 
    public function index()
    {
        $positions = Position::orderBy('id', 'desc')->get();
        return view('positions.index', compact('positions'));
    }
    // create
    public function create()
    {
        $divisions = Division::orderBy('name')->get();
        return view('positions.create', compact('divisions'));
    }
    // store
    public function store(Request $request)
    {
        $position = new Position();
        $position->name = $request->name;
        $position->description = $request->description;
        if($request->start){
            $position->start = $request->start;
        }
        if($request->data_start){
            $position->data_start = $request->data_start;
        }
        if($request->closed){
            $position->closed = $request->closed;
        }
        if($request->data_closed){
            $position->data_closed = $request->data_closed;
        }
        $position->save();
        // if has division 
        if($request->division_id){
            $position->divisions()->attach($request->division_id);
        }
      //  return $position;
        return redirect()->route('positions.index');
    }
    // show
    public function show($id)
    {
        $position = Position::find($id);
        return view('positions.show', compact('position'));
    }
    // edit
    public function edit($id)
    {
        $position = Position::find($id);
        $divisions = Division::orderBy('name')->get();
        return view('positions.edit', compact('position', 'divisions'));
    }
    // update
    public function update(Request $request, $id)
    {
        $position = Position::find($id);
        $position->name = $request->name;
        $position->description = $request->description;
        $position->start = $request->start;
        $position->data_start = $request->data_start;
        $position->closed = $request->closed;
        $position->data_closed = $request->data_closed;
        $position->save();
        // if has division
        if($request->division_id){
            $position->divisions()->sync($request->division_id);
        }
        return redirect()->route('positions.index');
    }
    // destroy
    public function destroy($id)
    {
        $position = Position::find($id);
        // delete all divisions
        $position->divisions()->detach();
        $position->delete();
        return redirect()->route('positions.index');
    }
   
    //get_position_from_divisions_api
    public function get_position_from_divisions_api(Request $request)
    {
      /*  $personal = Personal::all(); // Получаем всех сотрудников
        foreach ($personal as $person) {
            $divisions = $person->divisions; // Получаем коллекцию связанных подразделений
            $positions = $person->positions; // Получаем связанные позиции для каждого сотрудника
      
            foreach ($divisions as $division) { // Проходим по каждому подразделению сотрудника
                if ($positions) {
                    // Синхронизируем позиции для каждого подразделения, если связи ещё нет
                    $division->positions()->syncWithoutDetaching($positions->pluck('id')->toArray());
                }
            }
        }
         */ 
        if(!$request->divisions_id){
            return [];
        }
        $divisions_id = $request->divisions_id;
         $div=Division::whereIn('id', $divisions_id)->get();
      //  return $div;
        $positions = [];
        
        foreach ($div as $division) {
            // Проверяем, есть ли связанные позиции у подразделения
            if ($division->positions && count($division->positions) > 0) {
                foreach ($division->positions as $position) {
                    $positions[] = $position;
                }
            }
        }
        
        return $positions;
        
    }
}
