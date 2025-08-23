<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brief;
use App\Models\Jit;
use App\Models\Type;

class BriefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $briefs= Brief::all()->keyBy('id')->values();
       
        return view('briefs.index', compact('briefs'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $brief = Brief::find($id);
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = Type::where('parent_id', $actions_parent->id)->get();
        $jits = Jit::all()->keyBy('id')->values();
        // only id
         $myjits = $this->jits($brief)
         ->pluck('id')
            ->toArray();

       return view('briefs.edit', compact('brief','causes','actions','jits','myjits'));
    }

    public function jits($brief)
    {
        return Jit::join('jits_jitqws', 'jits.id', '=', 'jits_jitqws.jit_id')
                  ->join('jitqws', 'jitqws.id', '=', 'jits_jitqws.jitqw_id')
                  ->join('briefs_jitqws', 'jitqws.id', '=', 'briefs_jitqws.jitqw_id')
                  ->where('briefs_jitqws.brief_id', $brief->id)
                  ->select('jits.*')
                  
                  ->get(); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
       // return $request;
        $brief = Brief::find($id);
        $name_ru = $request->name_ru;
        $name_uk = $request->name_uk;
        $name_en = $request->name_en;
        $order = $request->order;
        $type = $request->type;
        $risk = $request->risk;
        $functional = $request->functional;
        $reasons = $request->causes;
        $actions = $request->actions;
        $brief->name_ru = $name_ru;
        $brief->name_uk = $name_uk;
        $brief->name_en = $name_en;
        $brief->order = $order;
        $brief->type = $type;
        $brief->risk = $risk;
        $brief->functional = $functional;
        $brief->save();
        //clear all previous relations
        $brief->reasons()-> detach();
        $brief->reasons()->sync($reasons);
        $brief->actions()-> detach();
        $brief->actions()->sync($actions);
        return redirect()->route('briefs.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
