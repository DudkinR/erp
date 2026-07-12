<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
// position model
use App\Models\Position;
use App\Models\Kndk;


class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $divisions = Division::with([
        'children',
        'parent',
        'positions',
        'structures',
        'personals',
        'systems'
        ])->get()->map(function($d){
            $d->positions = $d->positions ?? [];
            $d->personals = $d->personals ?? [];
            $d->systems   = $d->systems ?? [];
            return $d;
        });


        return view('divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $parents = Division::all()->keyBy('id')->values();
        $positions = Position::all()->keyBy('id')->values();
        return view('divisions.create', compact('parents', 'positions'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // find name
        $division = Division::where('name', $request->name)->first();
        // if name exists
        if ($division) {
            return redirect()->back()->with('error', 'Division already exists');
        }
        //  division  new
        $division = new Division();
        // update division
        $division->name = $request->name;
        $division->description = $request->description;
        $division->abv = $request->abv; 
        $division->slug = $request->slug;
        $division->parent_id = $request->parent_id;
        $division->save();
        // sync positions
        $division->positions()->sync($request->positions);
        return redirect()->route('divisions.index')->with('success', 'Division created successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $division = Division::find($id);
        $under_divisions = Division::where('parent_id', $id)->get();
        $count_personal = $this->personalDivisionCount($id);
        $rooms = $this->roomsInDivision($id);
        $buildings = $this->buildingsInDivision($id);
        return view('divisions.show', compact('division', 'under_divisions', 'count_personal', 'rooms', 'buildings'));
    }

    // personal division count all with under divisions
    public function personalDivisionCount(string $id)
    {
        $count=0;
        $division = Division::find($id);
        $under_divisions = Division::where('parent_id', $id)->get();
        $count += $division->personals->count();
        foreach ($under_divisions as $under_division) {
            $count += $this->personalDivisionCount($under_division->id);
        }
        return $count;
    }
    // all rooms in division
    public function roomsInDivision(string $id)
    {
        $rooms = [];
        $division = Division::find($id);
    
        // Ініціалізуємо як масив, якщо $division->rooms є null
        $rooms = $division->rooms ?? [];
    
        $under_divisions = Division::where('parent_id', $id)->get();
    
        foreach ($under_divisions as $under_division) {
            // Використовуємо array_merge тільки з масивами
            $rooms = array_merge($rooms, $this->roomsInDivision($under_division->id) ?? []);
        }    
        return $rooms;
    }
    
    // all buildengs where division is located
    public function buildingsInDivision(string $id,$buildings = [])
    {
        $rooms = $this->roomsInDivision($id);
        foreach ($rooms as $room) {
          //only unique buildings
            if (!in_array($room->building, $buildings)) {
                $buildings[] = $room->building;
            }
        }
        return $buildings;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $parents = Division::where('parent_id', 0)        
        ->orderBy('name', 'asc')->get();
        
        // Створюємо новий пустий об'єкт моделі та задаємо йому потрібні атрибути
        $noParent = new Division();
        $noParent->id = 0;
        $noParent->name = 'Без підрозділу';

        // Додаємо об'єкт на початок колекції
        $parents->prepend($noParent);
        $positions = Position::orderBy('name', 'asc')->get();
        $division = Division::find($id);
        $division->load('kndks');
        $kndks = Kndk::orderBy('id', 'asc')->get();
        $division->kndks->pluck('id')->toArray();
        return view('divisions.edit', compact('division', 'parents', 'positions', 'kndks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'abv'         => 'nullable|string|max:50',
            'slug'        => 'nullable|string|max:255',
            'parent_id'   => 'nullable|integer', 
            'positions'   => 'array',
            'positions.*' => 'exists:positions,id',
            'kndk_ids'       => 'array',
            'kndk_ids.*'     => 'exists:kndks,id',
        ]);

        $division = Division::findOrFail($id);

        // оновлюємо основні поля
        $division->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'abv'         => $validated['abv'] ?? null,
            'slug'        => $validated['slug'] ?? null,
            'parent_id'   => $validated['parent_id'] ?? 0,
        ]);

    // Якщо масив не прийшов (зняли всі галочки), передається пустий масив [], і sync() видалить усі старі зв'язки.
    $division->positions()->sync($validated['positions'] ?? []);
   // return $validated;
    $division->kndks()->sync($validated['kndk_ids'] ?? []);

        return redirect()
            ->route('divisions.index')
            ->with('success', 'Division updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $division = Division::findOrFail($id);

        // Очистка зв’язків many-to-many
        $division->positions()->detach();
        $division->structures()->detach();
        $division->personals()->detach();
        $division->systems()->detach();
        if (method_exists($division, 'kndks')) {
            $division->kndks()->detach();
        }

        // Видалення самого підрозділу
        $division->delete();

        return redirect()
            ->route('divisions.index')
            ->with('success', 'Division deleted successfully.');
    }

}
