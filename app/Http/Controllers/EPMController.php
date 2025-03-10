<?php

namespace App\Http\Controllers;

use App\Models\EPM;
use Illuminate\Http\Request;

class EPMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $epms = EPM::all();
        return view('epms.index', compact('epms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('epms.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $epm = EPM::create([
            'name' => (string) $request->name,
            'description' => (string) $request->description, 
            'divition' => $request->division ?? 0, // Додаємо значення за замовчуванням   
            'area' => (int) $request->wanoarea
        ]);
        
        
        
        return redirect('/epms')->with('success', 'epmloyee saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $epm = EPM::find($id);
        return view('epms.show', compact('epm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $epm = EPM::find($id);
        return view('epms.edit', compact('epm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $epm =  EPM::find($id);
        $epm->name = $request->name;
        $epm->description = $request->description;
        $epm->area = $request->wanoarea;
        $epm->division = $request->division;
        $epm->save();
        return redirect('/epms')->with('success', 'epmloyee updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {        //
        $epm = EPM::find($id);
        $epm->delete();
        return redirect('/epms')->with('success', 'epmloyee deleted!');
    }
}
