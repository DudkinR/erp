<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\System;
use App\Models\Division;
use App\Models\Type;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $items = System::all();
        $divisions = Division::all();
        $Objects = Type::whereHas('parent', function ($query) {
            $query->where('slug', 'Obyekt');
        })->get();

        return view('systems.index', compact('items', 'divisions', 'Objects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $divisions = Division::all();
        $slug='Obyekt';
        $Objects = Type::whereHas('parent', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->get();

        return view('systems.create', compact('divisions', 'Objects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $fillable = ['uk', 'ru', 'en', 'abv', 'group', 'svb'];
        //  public function divisions()        return $this->belongsToMany(Division::class, 'divisions_systems', 'system_id', 'division_id');

        $validatedData = $request->validate([
            'uk' => 'required|string|max:255',
            'ru' => 'required|string|max:255',
            'en' => 'required|string|max:255',
            'abv' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'svb' => 'nullable|string|max:255',
            'divisions' => 'array', // Validate divisions as an array
        ]);
        $system = System::create($validatedData);
        // Attach divisions to the system
        if (isset($validatedData['divisions'])) {
            $system->divisions()->attach($validatedData['divisions']);  
        }

        return redirect()->route('systems.index')->with('success', 'System created successfully.');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $system = System::findOrFail($id)->load('divisions');
        $Objects = Type::whereHas('parent', function ($query) {
            $query->where('slug', 'Obyekt');
        })->get();
        return view('systems.show', compact('system', 'Objects'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $system = System::findOrFail($id);
        $divisions = Division::all();
        $slug='Obyekt';
        $Objects = Type::whereHas('parent', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->get();

        return view('systems.edit', compact('system', 'divisions', 'Objects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //  
        $system = System::findOrFail($id);
        $validatedData = $request->validate([
            'uk' => 'required|string|max:255',
            'ru' => 'required|string|max:255',
            'en' => 'required|string|max:255',
            'abv' => 'nullable|string|max:255',
            'group' => 'nullable|string|max:255',
            'svb' => 'nullable|string|max:255',
            'divisions' => 'array', // Validate divisions as an array
        ]);
        $system->update($validatedData);
        // Sync divisions to the system
        if (isset($validatedData['divisions'])) {
            $system->divisions()->sync($validatedData['divisions']);
        } else {
            $system->divisions()->detach(); // Detach all divisions if none are selected    
        }
        return redirect()->route('systems.index')->with('success', 'System updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $system = System::findOrFail($id);
        $system->divisions()->detach(); // Detach all divisions before deleting
        $system->delete();
        return redirect()->route('systems.index')->with('success', 'System deleted successfully.');
    }
}
