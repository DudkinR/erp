<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adocument;
use App\Models\Apackage;
use App\Models\DocType;
use App\Models\Building;
use App\Models\Division;
use App\Models\DampAD;
use App\Models\DampAP;
use App\Models\Type;
use Illuminate\Support\Facades\DB;



class ArhivePackegeController extends Controller
{
     public function index()
    {
        ini_set('memory_limit', '256M'); // або '512M' при потребі

        $packages = Apackage::with('documents')
      //  ->limit(10)
        ->get()->map(function ($package) {
        $package->total_pages = $package->pages(); // додаємо нове поле
            return $package;
        });
        return view('arch.indexpackege', compact('packages'));
        
    }
    //storePackage
    public function store(Request $request)
    {
        $Apackage = new Apackage();
        $Apackage->national_name = $request->input('national_name');
        $Apackage->foreign_name = $request->input('foreign_name');
        $Apackage->save();
        // edit package
        return redirect()->route('archived-documents.packages.edit', $Apackage->id)->with('success', 'Пакет успішно створено.');
    }
     public function edit($id)
    {
        $package = Apackage::findOrFail($id);      
        return view('arch.editp', compact( 'package'));
    }
    public function update(Request $request, Apackage $package)
    {
        $package->foreign_name    = $request->input('foreign_name', '');
        $package->national_name   = $request->input('national_name', '');
        $package->save();
        return redirect()->route('archived-documents.packages.show', $package->id)
                        ->with('success', 'Пакетт успішно оновлено.');

    }
    
    public function show($id)
    {
        $package = Apackage::with('documents')->find($id);

        if (!$package) {
            return redirect()->route('archived-documents.packages')
                            ->with('error', 'Пакет не знайдено.');
        }

        return view('arch.package', compact('package'));
    }
        public function destroy($id)
    {

         $type = Apackage::find($id);
        $type->delete();
        return redirect()->route('archived-documents.index')->with('success', ' успішно видалено.');
    }

}
