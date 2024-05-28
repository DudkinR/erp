<?php

namespace App\Http\Controllers;
use App\Models\Store;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    //
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
    }
    // create store
    public function create()
    {
        return view('stores.create');
    }
    public function store(Request $request)
    {
            if( $request->name == null ){
                return redirect()->route('stores.create');
            }
        $store = new Store();
        if( $request->IDname == null ){
            $store->IDname = $request->name;
        }else{
        $store->IDname = $request->IDname;
        }
        $store->name = $request->name;

        $store->address = $request->address;
        $store->description = $request->description;
        $store->save();
        return redirect()->route('stores.index');
    }
    // edit store
    public function edit($id)
    {
        $store = Store::find($id);
        return view('stores.edit', compact('store'));
    }
    public function update(Request $request, $id)
    {
        $store = Store::find($id);
        $store->IDname = $request->IDname;
        $store->name = $request->name;
        $store->address = $request->address;
        $store->description = $request->description;
        $store->save();
        return redirect()->route('stores.index');
    }
    // delete store
    public function destroy($id)
    {
        $store = Store::find($id);
        $store->delete();
        return redirect()->route('stores.index');
    }
    // show store
    public function show($id)
    {
        $store = Store::find($id);
        return view('stores.show', compact('store'));
    }
}
