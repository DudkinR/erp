<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;

class RoomController extends Controller
{
    // index room
    public function index()
    {
        $rooms = Room::orderBy('id', 'desc')->get();
        return view('rooms.index', compact('rooms'));
    }
    // create room
    public function create()
    {
        return view('rooms.create');
    }
    // show room
    public function show($id)
    {
        $room = Room::find($id);
        return view('rooms.show', compact('room'));
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
