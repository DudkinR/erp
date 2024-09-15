<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Form;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;


class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = Division::where('parent_id', null)->
        orWhere('parent_id', 0)->        
        orderBy('name')->get();
        $forms = Form::orderBy('name')
        ->with('divisions')
        ->get();
        return view('forms.index', compact('divisions', 'forms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $divisions = Division::where('parent_id', null)->
        orWhere('parent_id', 0)->
        orderBy('name')->get();
        return view('forms.create', compact('divisions'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $form = new Form();
        $form->name = $request->name;
        $form->description = $request->description;
        $form->status = $request->status;
        $form->author_tn = Auth::user()->tn;
        $form->save();
        if($request->division==null && $request->division==""){
            $divisions = Division::where('parent_id', null)->
            orWhere('parent_id', 0)->
            orderBy('name')->get();
            $form->divisions()->attach($divisions->pluck('id'));

        }
        else{
            $form->divisions()->attach($request->division);
        }
        return redirect()->route('forms.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $form = Form::find($id)
        ->load('divisions', 'items');        
        return view('forms.show', compact('form'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
        $form = Form::find($id);
        $divisions = Division::orderBy('name')->get();  
        return view('forms.edit', compact('form', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $form = Form::find($id);
        $form->name = $request->name;
        $form->description = $request->description;
        $form->status = $request->status;
        $form->divisions()->sync($request->divisions);
        $form->save();
        return redirect()->route('forms.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $form = Form::find($id);
        $form->divisions()->detach();
        $form->items()->detach();
        $form->delete();
        return redirect()->route('forms.index');

    }
}
