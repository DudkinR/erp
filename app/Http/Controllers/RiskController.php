<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\Type;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;    


class RiskController extends Controller
{
    // index
    public function index()
    {
        $experiences = Experience::orderBy('npp', 'asc')->get();
        return view('risks.index', compact('experiences'));

    }
    // create
    public function create()
    {
        $equipments_parent = Type::where('slug', 'equipment')->first();
        $equipments = Type::where('parent_id', $equipments_parent->id)->get();
        $systems = System::all();
       $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = Type::where('parent_id', $actions_parent->id)->get();
        return view('risks.create', compact('equipments', 'systems', 'causes', 'actions'));
    }

    // store
    public function store(Request $request)
    {
        $experience = new Experience();
        $text_ru='';
        $text_uk='';
        $text_en='';
        if($request->lang && $request->lang=='ru'){
            $text_ru = $request->text;            
            $accepted=0;
        }
        if($request->lang && $request->lang=='uk'){
            $text_uk = $request->text;            
            $accepted=1;
        }
        if($request->lang && $request->lang=='en'){
            $text_en = $request->text;            
            $accepted=0;
        }
        $experience->text_ru = $text_ru;
        $experience->text_uk = $text_uk;
        $experience->text_en = $text_en;
        $experience->npp = $request->npp;
        $experience->year = $request->year;
        $experience->consequence = $request->consequence;
        $experience->accepted = $accepted;
        $experience->author_tn =Auth::user()->tn;
        $experience->save();
        $experience->systems()->sync($request->systems);
        $experience->equipments()->sync($request->equipments);
        $experience->actions()->sync($request->actions);
        $experience->reasons()->sync($request->reasons);
        return redirect()->route('risks.index');
    }
    // edit
    public function edit($id)
    {
       $experience = Experience::with('systems', 'equipments', 'actions', 'reasons')
     ->findOrFail($id);
      
        $equipments_parent = Type::where('slug', 'equipment')->first();
        $equipments = Type::where('parent_id', $equipments_parent->id)->get();
        $systems = System::all();
        $causes_parent = Type::where('slug', 'cause')->first();
        $causes = Type::where('parent_id', $causes_parent->id)->get();
        $actions_parent = Type::where('slug', 'action')->first();
        $actions = Type::where('parent_id', $actions_parent->id)->get();
        return view('risks.edit', compact('experience', 'equipments', 'systems', 'causes', 'actions'));
    }
    // update
    public function update(Request $request, $id)
    {
        $experience = Experience::find($id);
        $text_ru= $experience->text_ru;
        $text_uk= $experience->text_uk;
        $text_en= $experience->text_en;
        if($request->lang && $request->lang=='ru'){
            $text_ru = $request->text;            
            $accepted=0;
        }
        if($request->lang && $request->lang=='uk'){
            $text_uk = $request->text;            
            $accepted=1;
        }
        if($request->lang && $request->lang=='en'){
            $text_en = $request->text;            
            $accepted=0;
        }
        $experience->text_ru = $text_ru;
        $experience->text_uk = $text_uk;
        $experience->text_en = $text_en;
        $experience->npp = $request->npp;
        $experience->year = $request->year;
        $experience->consequence = $request->consequence;
        $experience->accepted = 2;
        $experience->author_tn =Auth::user()->tn;
        $experience->save();
        // dethach and attach
        $experience->systems()->detach();
        $experience->systems()->sync($request->systems);
        $experience->equipments()->detach();
        $experience->equipments()->sync($request->equipments);
        $experience->actions()->detach();
        $experience->actions()->sync($request->actions);
        $experience->reasons()->detach();
        $experience->reasons()->sync($request->reasons);
        return redirect()->route('risks.index');
    }
    // destroy
    public function destroy($id)
    {
        $experience = Experience::find($id);
        $experience->systems()->detach();
        $experience->equipments()->detach();
        $experience->actions()->detach();
        $experience->reasons()->detach();
        $experience->delete();
        return redirect()->route('risks.index');
    }


    
    
}
