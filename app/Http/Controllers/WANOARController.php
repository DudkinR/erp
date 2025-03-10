<?php

namespace App\Http\Controllers;

use App\Models\WANOAREA;
use Illuminate\Http\Request;

class WANOARController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $wanoareas = WANOAREA::all();
        return view('wanoareas.index', compact('wanoareas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('wanoareas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //        
        $wanoarea = new WANOAREA([
            'abv' => $request->abv,
            'name' => $request->name,
            'focus' => $request->focus,
            'description' => $request->description
        ]);
        
        $wanoarea->save();
        return redirect('/wanoarea')->with('success', 'WANOAREA saved!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $wanoarea = WANOAREA::find($id);
        return view('wanoareas.show', compact('wanoarea'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $wanoarea = WANOAREA::find($id);
        return view('wanoareas.edit', compact('wanoarea'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        $wanoarea = WANOAREA::find($request->id);
        $wanoarea->abv = $request->abv;
        $wanoarea->name = $request->name;
        $wanoarea->focus = $request->focus;
        $wanoarea->description = $request->description;
        
        $wanoarea->save();
        return redirect('/wanoarea')->with('success', 'WANOAREA updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $wanoarea = WANOAREA::find($id);
        $wanoarea->delete();
        return redirect('/wanoarea')->with('success', 'WANOAREA deleted!');

    }
}
