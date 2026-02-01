<?php

namespace App\Http\Controllers;

use App\Models\Procedure;
use Illuminate\Http\Request;

class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $procedures = Procedure::orderBy('id', 'desc')->get();
        return view('procedures.index', compact('procedures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('procedures.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        ]);
        Procedure::create($request->only('name', 'description'));
        return redirect()->route('procedures.index')->with('success', 'Procedure created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
       
       $procedure= Procedure::find($id)
       ->load('steps'); // завантажує всі кроки
       return view('procedures.show', compact('procedure'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request , Procedure $procedure)
    {
       $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        ]);
        $procedure->update($request->only('name', 'description'));
        return redirect()->route('procedures.index')->with('success', 'Procedure updated successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Procedure $procedure)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Procedure $procedure)
    {
        //
    }

    public function updateSteps(Request $request)
    {
        return $request;
    }
}
