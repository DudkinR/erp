<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Control;

class ControlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $controls = Control::all();
        return view('controls.index', compact('controls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
        return view('controls.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $control = new Control();
        $control->name = $request->name;
        $control->description = $request->description;
        $control->save();
        return redirect()->route('controls.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $control = Control::find($id);
        return view('controls.show', compact('control'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $control = Control::find($id);
        return view('controls.edit', compact('control'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $control = Control::find($id);
        $control->name = $request->name;
        $control->description = $request->description;
        $control->save();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $control = Control::find($id);
        $control->delete();
        return redirect()->route('controls.index');
    }
}
