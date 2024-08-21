<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Master
use App\Models\Master;
use Illuminate\Support\Facades\Auth;
use App\Models\Doc;
use App\Models\Personal;
use App\Models\Resource;

class MasterController extends Controller
{
    // index
    public function index()
    {
        $I_M= Auth::user()->personal_id;
        $masters = Master::where('author_id', $I_M)->get();
        return view('master.index', compact('masters'));
    }
    // create
    public function create()
    {
        $docs = Doc::all();
        $personals = Personal::all();
        $resources = Resource::all();
        return view('master.create', compact('docs', 'personals', 'resources'));
    }
    // store
    public function store(Request $request)
    {
        $master = new Master();
        $master->author_id = Auth::user()->personal_id;
        $master->text = $request->text;
        $master->urgency = $request->urgency;
        $master->deadline = $request->deadline;
        $master->save();
        return redirect()->route('master.index');
    }
    // show
    public function show($id)
    {
        $master = Master::find($id);
        return view('master.show', compact('master'));
    }
    // edit
    public function edit($id)
    {
        $master = Master::find($id);
        $docs = Doc::all();
        $personals = Personal::all();
        $resources = Resource::all();
        return view('master.edit', compact('master', 'docs', 'personals', 'resources'));
    }
    // update
    public function update(Request $request, $id)
    {
        $master = Master::find($id);
        $master->author_id = Auth::user()->personal_id;
        $master->text = $request->text;
        $master->basis = $request->basis;
        $master->who = $request->who;
        $master->urgency = $request->urgency;
        $master->deadline = $request->deadline;
        $master->estimate = $request->estimate;
        $master->start = $request->start;
        $master->end = $request->end;
        $master->done = $request->done;
        $master->comment = $request->comment;
        $master->save();
        $master->docs()->sync($request->doc_id);
        $master->personals()->sync($request->personal_id);
        $master->resources()->sync($request->resource_id);
        return redirect()->route('master.index');
    }
    // destroy
    public function destroy($id)
    {
        $master = Master::find($id);
        // delete all related records
        $master->docs()->detach();
        $master->personals()->detach();
        $master->resources()->detach();        
        $master->delete();
        return redirect()->route('master.index');
    }
    

}
