<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fact;

class FactsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $facts = Fact::all();
        return view('facts.index', compact('facts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('facts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*name',
        'description',
        'image',
        'status'*/
        $fact = new Fact();
        $fact->name = $request->name;
        $fact->description = $request->description;
         // current date + 30 days
//$fact -> dedline_date = date('Y-m-d', strtotime('+30 days'));
        $fact -> status = 'active';
        $fact->save();
        // load image if exists rename = fact_id_data and save
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $fact->id . '_data.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $fact->image = $name;
            $fact->save();
        }
        return redirect()->route('facts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $fact = Fact::find($id);
        return view('facts.show', compact('fact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $fact = Fact::find($id);
        return view('facts.edit', compact('fact'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $fact = Fact::find($id);
        $fact->name = $request->name;
        $fact->description = $request->description;
        $fact->save();
        // load image if exists rename = fact_id_data and save
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = $fact->id . '_data.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            // delete old image
            if (file_exists($destinationPath . '/' . $fact->image)) {
                unlink($destinationPath . '/' . $fact->image);
            }
            $image->move($destinationPath, $name);
            $fact->image = $name;
            $fact->save();
        }
        return redirect()->route('facts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $fact = Fact::find($id);
        $fact->delete();
        return redirect()->route('facts.index');
    }
}
