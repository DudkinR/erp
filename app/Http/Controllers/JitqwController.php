<?php

namespace App\Http\Controllers;

use App\Models\Brief;
use Illuminate\Http\Request;
use App\Models\Jitqw;

class JitqwController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $jitqws = Jitqw::all();
       
        return view('jitqws.index', compact('jitqws'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $briefs = Brief::all();
        return view('jitqws.create', compact('briefs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $jitqw = new Jitqw();
        $jitqw->description_ru = $request->description_ru;
        $jitqw->description_uk = $request->description_uk;
        $jitqw->description_en = $request->description_en;
        $jitqw->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $jitqw = Jitqw::find($id);
        return view('jitqws.show', compact('jitqw'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $jitqw = Jitqw::find($id);
        $briefs = Brief::all();
        return view('jitqws.edit', compact('jitqw', 'briefs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
          $jitqw = Jitqw::find($id);
        $jitqw->description_ru = $request->description_ru;
        $jitqw->description_uk = $request->description_uk;
        $jitqw->description_en = $request->description_en;
        $jitqw->save();
        $jitqw->briefs()->detach();
        $jitqw->briefs()->sync($request->briefs);
       return redirect()->route('jitqws.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
