<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criteria;

class CriteriaController extends Controller
{
    //index
    public function index()
    {
        $criteries = Criteria::all();
            return view('criteria.index', compact('criteries'));
    }
    //create
    public function create()
    {
        return view('criteria.create');
    }
    //store  'name',        'description'
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        Criteria::create($request->all());
        return redirect()->route('criteria.index');
    }
    //edit
    public function edit(Criteria $criteria)
    {
        return view('criteria.edit', compact('criteria'));
    }
    //update
    public function update(Request $request, Criteria $criteria)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $criteria->update($request->all());
        return redirect()->route('criteria.index');
    }
    //destroy
    public function destroy(Criteria $criteria)
    {
        $criteria->delete();
        return redirect()->route('criteria.index');
    }
    


}
