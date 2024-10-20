<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Project;
use App\Models\Step;
use App\Models\Control;
use App\Models\Position;
use App\Models\Personal;    
use App\Models\Division;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $events = Event::orderBy('id', 'desc')->get();
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'status' => 'required',
            'control_date' => 'required'           
        ]);
        $event = new Event();
        $event->name = $request->name;
        $event->description = $request->description;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->status = $request->status;
        $event->control_date = $request->control_date;
        $event->save();
        $pairs = $this->pairs($request->divisions_id, $request->positions_id);
        foreach ($pairs as $pair) {
            // Используйте метод projects() для вызова attach()
            $event->projects()->attach($request->projects_id, [
                'division_id' => $pair[0], 
                'position_id' => $pair[1]
            ]);
        
            // Используйте метод steps() для вызова attach()
            $event->steps()->attach($request->steps_id, [
                'division_id' => $pair[0], 
                'position_id' => $pair[1]
            ]);
        }
        return redirect()->route('events.index');
    }

    public function pairs($divisions_id, $positions_id)
    {
        $divisions = Division::whereIn('id', $divisions_id)->get();
        $positions = Position::whereIn('id', $positions_id)->get();
        // find pairs  divisions_positions
        $pairs = [];
        foreach($divisions as $division){
            foreach($positions as $position){
                if($division->positions->contains($position->id)){
                    $pairs[] = [$division->id , $position->id];
                }
            }
        } 
        return $pairs;
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
        $event = Event::find($id);
        return view('events.edit', compact('event'));
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
        // detach
        $event = Event::find($id);
        $event->projects()->detach();
        $event->steps()->detach();
        $event->delete();
        return redirect()->route('events.index');
        
    }
}
