<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criteria;

class CriteriaController extends Controller
{
    //index
    public function index()
    {
        $criteries = Criteria::with('criteria')
        ->orderBy('id', 'DESC')
        ->get();
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
        $name = $request->name;
        $description = $request->description;
        Criteria::create([
            'name' => $name,
            'description' => $description
        ]);

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
        $name = $request->name;
        $description = $request->description;
        $criteria->update([
            'name' => $name,
            'description' => $description
        ]);

        return redirect()->route('criteria.index');
    }
    //destroy
    public function destroy($id)
    {
        $criteria = Criteria::find($id);
        $criteria->delete();
        return redirect()->route('criteria.index');
    }
    


}
