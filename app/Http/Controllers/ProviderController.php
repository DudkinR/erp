<?php

namespace App\Http\Controllers;
use App\Models\Provider;
//Contract
use App\Models\Contract;
//Document
use App\Models\Doc;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $provides = Provider::all();
         $contracts = Contract::all();
         $docs = Doc::all();
         return view('provider.index',compact('provides','contracts','docs'));
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
       $provider = new Provider();
       $provider->full_name = $request->full_name;
       $provider->short_name = $request->short_name;
       $provider->ownership_form = $request->ownership_form;
       $provider->edrpou_code = $request->edrpou_code;
       $provider->country = $request->country;
       $provider->products_services = $request->products_services;
       $provider->decision_number = $request->decision_number;
       $provider->decision_date = $request->decision_date;
       $provider->valid_until = $request->valid_until;
       $provider->notes = $request->notes;
       $provider->status = 1; // Активний за замовчуванням
       $provider->save();
       return redirect()->route('providers.index')->with('success', 'Постачальник'.' доданий успішно.');

    }

    // store_contract
    public function store_contract(Request $request)
    {
       $contract = new Contract();
       $contract->contract_number = $request->contract_number;
       $contract->contract_date = $request->contract_date;
       $contract->provider_id = $request->provider_id;
       $contract->subject = $request->subject;
       $contract->save();
       return redirect()->route('providers.index')->with('success', 'Договір доданий успішно.');    
    }
    // store_document
    public function store_document(Request $request)
    {
       $doc = new Doc();
       $doc->name = $request->doc_name;
       $doc->description = $request->description;
       $doc->slug = $request->slug;
       $doc->status = 1; // Активний за замовчуванням
       $doc->save();
       return redirect()->route('providers.index')->with('success', 'Документ доданий успішно.');    
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     public function import()
     {
        return  view('provider.import');
     }

     public function importData(Request $request)
    {
       //return $request->all();
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');
        $header = fgetcsv($file); // Пропускаємо заголовок

        $count = 0;
        while (($row = fgetcsv($file)) !== false) {
            // Очистка даних
            $row = array_map('trim', $row);

            // Мапінг колонок
            [
                $full_name,
                $short_name,
                $ownership_form,
                $edrpou_code,
                $country,
                $products_services,
                $decision_number,
                $decision_date,
                $valid_until,
                $notes
            ] = $row;

            // Перевірка на дублікати
            $exists = Provider::where('full_name', $full_name)
                ->orWhere('edrpou_code', $edrpou_code)
                ->exists();

            if (!$exists) {
                Provider::create([
                    'full_name' => $full_name,
                    'short_name' => $short_name,
                    'ownership_form' => $ownership_form,
                    'edrpou_code' => $edrpou_code,
                    'country' => $country,
                    'products_services' => $products_services,
                    'decision_number' => $decision_number,
                    'decision_date' => $this->parseDate($decision_date),
                    'valid_until' => $this->parseDate($valid_until),
                    'notes' => $notes,
                ]);
                $count++;
            }
        }

        fclose($file);

        return back()->with('success', "Імпортовано $count нових постачальників.");
    }

    private function parseDate($value)
    {
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
