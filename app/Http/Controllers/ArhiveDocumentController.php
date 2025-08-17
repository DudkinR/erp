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
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ArhiveDocumentController extends Controller
{
    public function panel()
    {
         $documents = Adocument::with('packages')
        //->limit(13000)
        ->get();
        return view('arch.panel', compact('documents'));
    }

    public function index()
    {
        ini_set('memory_limit', '256M'); // або '512M' при потребі

        $documents = Adocument::with('packages')
        //->limit(13000)
        ->get();
        return view('arch.index', compact('documents'));
    }
    // archivedPackages
    public function archivedPackages()
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
    public function storePackage(Request $request)
    {
        $request->validate([
            'national_name' => 'required|string|max:255',
            'foreign_name' => 'nullable|string|max:255',
        ]);

        $Apackage = new Apackage();
        $Apackage->national_name = $request->input('national_name');
        $Apackage->foreign_name = $request->input('foreign_name');
        $Apackage->save();
        // edit package
        return redirect()->route('archived-documents.packages')->with('success', 'Пакет успішно створено.');
    }

    public function create()
    {
      /*  $docs = Adocument::all();
        foreach ($docs as $doc) {
         $foreing_name = $doc->foreign_name;
         $national_name = $doc->national_name;
         $type= DocType::where('foreign_name', $foreing_name)
            ->first();
            if (!$type) {
                DocType::create([
                    'old_id' => NULL,
                    'foreign_name' => $foreing_name,
                    'national_name' => $national_name,
                ]);
            }
        }
        */
        $packages = Apackage::all();
         $docTypes = DocType::all();
       $buildings = Building::all();
        $develops = Division::all();
     $docs = Adocument::all();

        return view('arch.create', compact('packages', 'docTypes', 'buildings', 'develops', 'docs'));
    }

    public function show($id)
    {
        $document = Adocument::with('packages')->find($id);
        return view('arch.show', compact('document'));
    }

    public function store(Request $request)
{
    // Валідація: хоча б одне з назв пакета (укр або іноземне) обов’язкове
    $request->validate([
        'new_package_national_name' => 'required_without:new_package_foreign_name',
        'new_package_foreign_name'  => 'required_without:new_package_national_name',
    ], [
        'new_package_national_name.required_without' => 'Вкажіть назву пакета українською або іноземною.',
        'new_package_foreign_name.required_without'  => 'Вкажіть назву пакета українською або іноземною.',
    ]);

    // Якщо створюється новий пакет
    if ($request->has('new_package_checkbox')) {
        $package = Apackage::create([
            'foreign_name'  => $request->input('new_package_foreign_name', ''),
            'national_name' => $request->input('new_package_national_name', ''),
        ]);
    } else {
        $package = Apackage::find($request->input('package_id'));
    }
    
    // Створення документа з заміною null → ""
    $document = Adocument::create([
        'foreign_name'    => $request->input('foreign_name', ''),
        'national_name'   => $request->input('national_name', ''),
        'reg_date'        => $request->input('reg_date', ''),
        'pages'           => $request->input('pages', 0),
        'production_date' => $request->input('production_date', ''),
        'kor'             => $request->input('kor', ''),
        'part'            => $request->input('part', ''),
        'contract'        => $request->input('contract', ''),
        'develop'         => $request->input('develop', ''),
        'object'          => $request->input('object', ''),
        'unit'            => $request->input('unit', ''),
        'stage'           => $request->input('stage', ''),
        'code'            => $request->input('code', ''),
        'inventory'       => $request->input('inventory', ''),
        'path'            => '',
        'storage_location'=> $request->input('storage_location', ''),
    ]);
        $file_name=  $request->input('unit', '')."_".$request->input('object', '')."_".$request->input('stage', '')."_".$request->input('code', '').$document ->id;
        // загрузити файл, якщо він є зберегти в сторедж документація  і зберегти путь  розширеніе залишити
        if ($request->hasFile('scan')) {
            $file = $request->file('scan');
            $file_path = $file->storeAs('documents', $file_name.'.'.$file->getClientOriginalExtension());
            $document->path = $file_path;
            $document->save();
        }
        return redirect()->route('archived-documents.index')->with('success', 'Документ успішно створено та збережено в архіві.');
    }
   public function editPackage($id)
    {
        $package = Apackage::findOrFail($id);      
        return view('arch.editp', compact( 'package'));
    }
    public function updatep(Request $request, Apackage $package)
    {
          $package->foreign_name    = $request->input('foreign_name', '');
        $package->national_name   = $request->input('national_name', '');
        $package->save();
        return redirect()->route('archived-documents.packages.show', $package->id)
                        ->with('success', 'Пакетт успішно оновлено.');

    }
    public function edit($id)
    {
        $document = Adocument::findOrFail($id)->with('packages')->first();
        $package = $document->packages->first();
        $packages = Apackage::all();
        return view('arch.edit', compact('document', 'packages', 'package'));
    }


   public function update(Request $request, Adocument $document)
    {
        // Валідація
        $request->validate([
            'foreign_name' => 'required|string|max:255',
            'reg_date' => 'nullable|date',
            // інші правила за потребою...
            // Валідація пакетів: хоча б одна з назв обов’язкова для кожного пакета
            'packages.*.foreign_name' => 'required_without:packages.*.national_name|string|nullable',
            'packages.*.national_name' => 'required_without:packages.*.foreign_name|string|nullable',
        ], [
            'packages.*.foreign_name.required_without' => 'Вкажіть назву пакета українською або іноземною.',
            'packages.*.national_name.required_without' => 'Вкажіть назву пакета українською або іноземною.',
        ]);

        // Оновлення полів документа (null → '')
        $document->foreign_name    = $request->input('foreign_name', '');
        $document->national_name   = $request->input('national_name', '');
        $document->reg_date        = $request->input('reg_date', '');
        $document->pages           = $request->input('pages', 0);
        $document->production_date = $request->input('production_date', '');
        $document->kor             = $request->input('kor', '');
        $document->part            = $request->input('part', '');
        $document->contract        = $request->input('contract', '');
        $document->develop         = $request->input('develop', '');
        $document->object          = $request->input('object', '');
        $document->unit            = $request->input('unit', '');
        $document->stage           = $request->input('stage', '');
        $document->code            = $request->input('code', '');
        $document->inventory       = $request->input('inventory', '');
        $document->storage_location= $request->input('storage_location', '');

        // Ім'я файлу для збереження
        $file_name = $request->input('unit', '') . "_" . $request->input('object', '') . "_" . $request->input('stage', '') . "_" . $request->input('code', '') . $document->id;

        // Оновлення файлу, якщо завантажено новий
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $file_path = $file->storeAs('documents', $file_name . '.' . $file->getClientOriginalExtension());
            $document->path = $file_path;
        }

        $document->save();

        // Оновлення пакетів (якщо надіслані)
        $packagesInput = $request->input('packages', []);
        foreach ($packagesInput as $pkgData) {
            if (isset($pkgData['id'])) {
                $package = Apackage::find($pkgData['id']);
                if ($package) {
                    $package->foreign_name = $pkgData['foreign_name'] ?? '';
                    $package->national_name = $pkgData['national_name'] ?? '';
                    $package->save();
                }
            }
        }

        return redirect()->route('archived-documents.show', $document->id)
                        ->with('success', 'Документ успішно оновлено.');
    }


public function destroy($id)
    {
        Adocument::destroy($id);
        return response()->json(null, 204);
    }
    //import
  public function import()
    {
        return view('arch.import');
    }

    public function importStore(Request $request)
    {
     
       // return 8888;
        // Maximum execution time of 360 seconds exceeded
        ini_set('max_execution_time', 360);
        $path = storage_path('app/public/archive0.csv');

        if (!file_exists($path)) {
            return response()->json(['error' => 'Файл не знайдено'], 404);
        }
      //  $path = $request->file('file')->getRealPath();

        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); // пропускаємо заголовок

            DB::beginTransaction();
            try {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    // Якщо треба — перетворюємо з Windows-1251
                 // return $row ;

                    // 1. Пакет
                    $package = Apackage::firstOrCreate(
                        ['id' => $row[1]], // зберігаємо ID з CSV
                        [
                            'foreign_name'  => $row[6], // Наименование проекта
                            'national_name' => "",
                        ]
                    );

                    // 2. Документ
                    $document = Adocument::firstOrCreate(
                        ['id' => $row[0]], // ID документа з CSV
                        [
                            'foreign_name'     => $row[11], // NAME документа
                            'national_name'    => "",
                            'reg_date'         => $this->parseDate($row[2]),
                            'pages'            => 0,
                            'kor'              => $row[3],
                            'part'             => $row[4],
                            'contract'         => $row[5],
                            'develop'          => "",
                            'object'           => $row[8],
                            'unit'             => $row[9],
                            'stage'            => $row[10],
                            'code'             => $row[12],
                            'inventory'        => $row[13],
                            'path'             => "",
                            'storage_location' => "",
                            'status'           => 'active', // або інший статус за замовчуванням
                        ]
                    );

                    // 3. Прив’язка
                    $document->packages()->syncWithoutDetaching([$package->id]);
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => $e->getMessage()]);
            }

            fclose($handle);
        }

        return back()->with('success', 'Імпорт завершено');
    }

    private function parseDate($date)
    {
        if (!$date) return null;
        $d = \DateTime::createFromFormat('d.m.Y', $date);
        return $d ? $d->format('Y-m-d') : null;
    }

    public function showPackage($id)
    {
        $package = Apackage::findOrFail($id)
            ->load('documents'); // Завантажуємо документи, пов'язані з пакетом
       // return  $package->pages();  
        return view('arch.package', compact('package'));
    } 


    public function exportPDF(Request $request)
    {
        // Отримуємо JSON і декодуємо
        $data = json_decode($request->getContent(), true)['data'] ?? [];

        if (empty($data)) {
            return response()->json(['message' => 'No data provided for export'], 400);
        }

        //\Log::debug('Received data for PDF export', ['data' => $data]);

        try {
            $pdf = Pdf::loadView('arch.archive_pdf', ['data' => $data])
                ->setPaper('A4', 'landscape')
                ->setOptions([
                    'defaultFont' => 'DejaVu Sans',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true
                ]);
            $dompdf = $pdf->getDomPDF();
            $canvas = $dompdf->getCanvas();
            $cpdf = $canvas->get_cpdf();

            // Додаємо метадані
            $cpdf->setTitle('Архівний документ');
            $cpdf->setAuthor('Система архівації');
            $cpdf->setSubject('Документ з ID запиту');
            $cpdf->setKeywords('архів, pdf, id, водяний знак');      
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: '.$e->getMessage());
            return response()->json(['message' => 'PDF generation failed', 'error' => $e->getMessage()], 500);
        }

        return $pdf->download('archive.pdf');
    }
    /*

        Route::get('/archived-documents-load', 'App\Http\Controllers\ArhiveDocumentController@load')->name('archived-documents.load');
         */
    public function load(Request $request)
    {
        //set min time php
        ini_set('max_execution_time', 300); // 5 minutes        

        $date = $request->query('date');
        $this->new_dump();
        $this->reload_dump($date);
        return redirect()->route('archived-documents.index')->with('success', 'Документи успішно завантажені з резервної копії. ('.$date.')');  
    }

    public function dump()
    {
        $dumps = DampAP::getUniqueDates();
         return view('arch.dumpindex', compact('dumps'));
    }
    public function dumpStore(Request $request)
    {
       $this->new_dump();
        return redirect()->route('archived-documents.dump.index')->with('success', 'Резервна копія успішно створена.');
    }

    //dumpDestroy
    public function dumpDestroy($date)
    {
        DampAD::where('damp_date', $date)->delete();
        DampAP::where('damp_date', $date)->delete();

        return redirect()->route('archived-documents.dump.index')->with('success', 'Резервна копія успішно видалена.');
    }
    //dumpShow
    public function dumpShow($date)
    {
        $documents = DampAD::where('damp_date', $date)
       ->with('package')
       
       ->get();
        $packages = DampAP::where('damp_date', $date)->get();

        return view('arch.dumpShow', compact('documents', 'packages'));
    }

    public function new_dump()
    {
        // Логіка для створення нового дампу
        $date = now('Europe/Kiev')->subHours(3)->format('Y-m-d H:i:s');

        $packages = Apackage::all();
       foreach ($packages as $package) {
            DampAP::create([
                'damp_date' => $date,
                'id_npp' => $package->id,
                'foreign_name' => $package->foreign_name,
                'national_name' => $package->national_name,
            ]);
        }
        $docs = Adocument::with('packages')
            ->get();
            
        foreach ($docs as $doc) {
           // echo $doc->packages->first()->id;
          DampAD::create([
                'damp_date' => $date,
                'id_npp' => $doc->id, // додано для відповідності DampAD
                'foreign_name' => $doc->foreign_name,
                'national_name' => $doc->national_name,
                'doc_type_id' => $doc->doc_type_id,
                'reg_date' => $doc->reg_date,
                'production_date' => $doc->production_date,
                'kor' => $doc->kor,
                'part' => $doc->part,
                'contract' => $doc->contract,
                'develop' => $doc->develop,
                'object' => $doc->object,
                'unit' => $doc->unit,
                'stage' => $doc->stage,
                'code' => $doc->code,
                'inventory' => $doc->inventory,
                'path' => $doc->path,
                'storage_location' => $doc->storage_location,
                'status' => $doc->status? $doc->status : 'active', // якщо статус не вказано, встановлюємо 'active'
                'package_id' => $doc->packages->first()->id ?? null, // якщо пакет не вказано, встановлюємо null
            ]);
        }
    }

    public function reload_dump($date)
    {
        // Видаляємо всі старі дані
        Adocument::query()->delete();
        Apackage::query()->delete();

        // Завантажуємо пакети
        $packages = DampAP::where('damp_date', $date)->get();
        foreach ($packages as $package) {
            Apackage::create([
                'id' => $package->id, // бо id_npp у тебе null
                'foreign_name' => $package->foreign_name,
                'national_name' => $package->national_name,
            ]);
        }

        // Завантажуємо документи
        $documents = DampAD::where('damp_date', $date)->get();
        foreach ($documents as $doc) {
            $d = Adocument::create([
                'id' => $doc->id, // а не id_npp
                'foreign_name' => $doc->foreign_name,
                'national_name' => $doc->national_name,
                'doc_type_id' => $doc->doc_type_id,
                'reg_date' => $doc->reg_date,
                'production_date' => $doc->production_date,
                'kor' => $doc->kor,
                'part' => $doc->part,
                'contract' => $doc->contract,
                'develop' => $doc->develop,
                'object' => $doc->object,
                'unit' => $doc->unit,
                'stage' => $doc->stage,
                'code' => $doc->code,
                'inventory' => $doc->inventory,
                'path' => $doc->path,
                'storage_location' => $doc->storage_location,
                'status' => $doc->status ?? 'active',
            ]);

            if ($doc->package_id && Apackage::find($doc->package_id)) {
                $d->packages()->attach($doc->package_id);
            }
        }
    }


}
