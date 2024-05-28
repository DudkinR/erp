<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    // create Product
    public function create()
    {
        return view('products.create');
    }
    public function store(Request $request)
    {
        if( $request->name == null ){
            return redirect()->route('products.create');
        }
        $Product = new Product();
        if( $request->IDname == null ){
            $Product->IDname = $request->name;
        }else{
            $Product->IDname = $request->IDname;
        }
        $Product->name = $request->name;
        $Product->description = $request->description;
        if( $request->manufacture_date !== null ){
            $Product->manufacture_date = $request->manufacture_date;
        }
        if( $request->expiration_date !== null ){
            $Product->expiration_date = $request->expiration_date;
        }
        if( $request->verification_date !== null ){
        $Product->verification_date = $request->verification_date;
        }
        if( $request->last_verification_date !== null ){
        $Product->last_verification_date = $request->last_verification_date;
        }
        if( $request->next_verification_date !== null ){
        $Product->next_verification_date = $request->next_verification_date;
        }
        if( $request->project !== null ){
        $Product->project = $request->project;
        }
        $Product->save();
        return redirect()->route('products.index');
    }
    // edit Product
    public function edit($id)
    {
        $Product = Product::find($id);
        return view('products.edit', compact('Product'));
    }
    public function update(Request $request, $id)
    {
        $Product = Product::find($id);
        $Product->IDname = $request->IDname;
        $Product->name = $request->name;
        $Product->description = $request->description;
        if( $request->manufacture_date !== null ){
            $Product->manufacture_date = $request->manufacture_date;
        }
        if( $request->expiration_date !== null ){
            $Product->expiration_date = $request->expiration_date;
        }
        if( $request->verification_date !== null ){
        $Product->verification_date = $request->verification_date;
        }
        if( $request->last_verification_date !== null ){
        $Product->last_verification_date = $request->last_verification_date;
        }
        if( $request->next_verification_date !== null ){
        $Product->next_verification_date = $request->next_verification_date;
        }
        if( $request->project !== null ){
        $Product->project = $request->project;
        }
        $Product->save();
        return redirect()->route('products.index');
    }
    // delete Product
    public function destroy($id)
    {
        $Product = Product::find($id);
        $Product->delete();
        return redirect()->route('products.index');
    }



}
