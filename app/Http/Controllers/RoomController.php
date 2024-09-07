<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Building;

class RoomController extends Controller
{
    // index room
    public function index()
    {
        $buildings = Building::orderBy('name', 'asc')->get(); 
        return view('rooms.index', compact('buildings'));
    }
    // create room
    public function create()
    {
        return view('rooms.create');
    }
    // show room
    public function show($id)
    {
       $building = Building::find($id)
        ->with('rooms', 'rooms.personals', 'rooms.personals.divisions')
        ->get();

      return   view('rooms.show', compact('building'));
    }
    public function store(Request $request)
    {
        if( $request->name == null ){
            return redirect()->route('rooms.create');
        }
        $room = new Room();
        if( $request->IDname == null ){
            $room->IDname = $request->name;
        }else{
            $room->IDname = $request->IDname;
        }
        $room->name = $request->name;
        $room->address = $request->address;
        $room->description = $request->description;
        $room->save();
        return redirect()->route('rooms.index');
    }
    // edit room
    public function edit($id)
    {
        $room = Room::find($id);
        return view('rooms.edit', compact('room'));
    }
    public function update(Request $request, $id)
    {
        $room = Room::find($id);
        $room->IDname = $request->IDname;
        $room->name = $request->name;
        $room->address = $request->address;
        $room->description = $request->description;
        $room->save();
        return redirect()->route('rooms.index');
    }
    // delete room
    public function destroy($id)
    {
        $room = Room::find($id);
        $room->delete();
        return redirect()->route('rooms.index');
    }
}
