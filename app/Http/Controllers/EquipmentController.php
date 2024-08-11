<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    //
    public function index()
    {
        $equipments = Equipment::orderBy('id', 'desc')->get();
        return view('equipments.index', compact('equipments'));
    }
    // create equipment
    public function create()
    {
        return view('equipments.create');
    }
    public function store(Request $request)
    {
        if( $request->name == null ){
            return redirect()->route('equipments.create');
        }
        $equipment = new Equipment();
        if( $request->IDname == null ){
            $equipment->IDname = $request->name;
        }else{
            $equipment->IDname = $request->IDname;
        }
        $equipment->name = $request->name;
        $equipment->address = $request->address;
        $equipment->description = $request->description;
        if( $request->manufacture_date !== null ){
        $equipment->manufacture_date = $request->manufacture_date;
        }
        if( $request->expiration_date !== null ){
        $equipment->expiration_date = $request->expiration_date;
        }
        if( $request->verification_date !== null ){
        $equipment->verification_date = $request->verification_date;
        }
        if( $request->last_verification_date !== null ){
        $equipment->last_verification_date = $request->last_verification_date;
        }
        if( $request->next_verification_date !== null ){
        $equipment->next_verification_date = $request->next_verification_date;
        }
        $equipment->save();
        return redirect()->route('equipments.index');
    }
    // edit equipment
    public function edit($id)
    {
        $equipment = Equipment::find($id);
        return view('equipments.edit', compact('equipment'));
    }
    public function update(Request $request, $id)
    {
        $equipment = Equipment::find($id);
        $equipment->IDname = $request->IDname;
        $equipment->name = $request->name;
        $equipment->address = $request->address;
        $equipment->description = $request->description;
        if( $request->manufacture_date !== null ){
        $equipment->manufacture_date = $request->manufacture_date;
        }
        if( $request->expiration_date !== null ){
        $equipment->expiration_date = $request->expiration_date;
        }
        if( $request->verification_date !== null ){
        $equipment->verification_date = $request->verification_date;
        }
        if( $request->last_verification_date !== null ){
        $equipment->last_verification_date = $request->last_verification_date;
        }
        if( $request->next_verification_date !== null ){
        $equipment->next_verification_date = $request->next_verification_date;
        }
        $equipment->save();
        return redirect()->route('equipments.index');
    }
    // delete equipment
    public function destroy($id)
    {
        $equipment = Equipment::find($id);
        $equipment->delete();
        return redirect()->route('equipments.index');
    }
    // show equipment
    public function show($id)
    {
        $equipment = Equipment::find($id);
        return view('equipments.show', compact('equipment'));
    }
    

}
