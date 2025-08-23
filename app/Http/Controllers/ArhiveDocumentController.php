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
use Barryvdh\DomPDF\Facade\Pdf;

/// php artisan make:controller ArhivePackegeController

class ArhiveDocumentController extends Controller
{
    public function panel()
    {
         ini_set('memory_limit', '256M'); // або '512M' при потребі
         $documents = Adocument::with('packages')
         ->orderBy('id', 'desc')
        ->get();
        return view('arch.panel', compact('documents'));
    }

    public function index()
    {
        ini_set('memory_limit', '256M'); // або '512M' при потребі

        $documents = Adocument::with('packages')
        ->orderBy('id', 'desc')
        ->get();
        return view('arch.index', compact('documents'));
    }
    // archivedPackages
    

    public function create()
    {
         ini_set('memory_limit', '256M'); // або '512M' при потребі
        $parent_type_doc = Type::where('name', 'Вид документа')->value('id');
        $docTypes = $parent_type_doc ? Type::where('parent_id', $parent_type_doc)->get() : collect();

        $parent_type_Developer = Type::where('name', 'Розробник')->value('id');
        $develops = $parent_type_Developer ? Type::where('parent_id', $parent_type_Developer)->get() : collect();

        $parent_type_Contractor = Type::where('name', 'Виконавець')->value('id');
        $Contractors = $parent_type_Contractor ? Type::where('parent_id', $parent_type_Contractor)->get() : collect();
        
        $parent_type_object = Type::where('name', 'Об\'єкт')->value('id');
        $objectTypes = $parent_type_object ? Type::where('parent_id', $parent_type_object)->get() : collect();
        
        $parent_type_stage = Type::where('name', 'Стадія')->value('id');
        $stageTypes = $parent_type_stage  ? Type::where('parent_id', $parent_type_stage )->get() : collect();
        $parent_type_arhive = Type::where('name', 'Архів')->value('id');
        $archiveTypes = $parent_type_arhive ? Type::where('parent_id', $parent_type_arhive)->get() : collect();

        $packages = Apackage::all()->keyBy('id')->values();
        $buildings = Building::all()->keyBy('id')->values();
       // $develops = Division::all()->keyBy('id')->values();
        $docs = Adocument::all()->keyBy('id')->values();
        return view('arch.create', compact('packages',  'stageTypes', 'objectTypes',  'docTypes', 'buildings', 'develops', 'docs','Contractors', 'parent_type_doc', 'parent_type_Developer', 'parent_type_Contractor', 'parent_type_object', 'parent_type_arhive', 'archiveTypes'));
    }

    public function show($id)
    {

        $document = Adocument::with('packages')->find($id)
        ->load('relatedDocs');
         if (!$document) {
            return redirect()->route('archived-documents.index')
                            ->with('error', 'Пакет не знайдено.');
        }

        return view('arch.show', compact('document'));
    }

    public function store(Request $request)
{

        if($request->package_id==0){
            if ($request->has('package_foreign_name') || $request->has('package_national_name')) {
                $package = Apackage::create([
                    'foreign_name'  => $request->input('new_package_foreign_name', ''),
                    'national_name' => $request->input('new_package_national_name', ''),
                ]);
             } 
        }
        else {
            $package = Apackage::find($request->input('package_id'));
        }

        $location = $request->input('storage_location')."_".$request->input('location', '');

        // Створення документа з заміною null → ""
        $document = Adocument::create([
            'foreign_name'    => $request->input('foreign_name', ''),
            'national_name'   => $request->input('national_name', ''),
            'reg_date'        => $request->input('reg_date', ''),
            'pages'           => $request->input('pages', 0)?: 0,
            'doc_type_id'     => $request->input('doc_type_id', null),
            'notes'           => $request->input('notes', ''),
            'production_date' => $request->input('production_date', ''),
            'kor'             => $request->input('kor', ''),
            'part'            => $request->input('part', ''),
            'contract'        => $request->input('contract', ''),
            'develop'         => $request->input('develop', ''),
            'object'          => $request->input('object', ''),
            'unit'            => $request->input('unit', ''),
            'stage'           => $request->input('stage', ''),
            'code'            => $request->input('code', ''),
            'inventory'       => $request->input('inventory_number', ''),
            'archive_number'  => $request->input('archive_number', ''),
            'path'            => $request->input('scan', ''),
            'storage_location'=> $location,
        ]);
        $file_name=  $request->input('unit', '')."_".$request->input('object', '')."_".$request->input('stage', '')."_".$request->input('code', '').$document ->id;
        // загрузити файл, якщо він є зберегти в сторедж документація  і зберегти путь  розширеніе залишити
        if ($request->hasFile('scan')) {
            $file = $request->file('scan');
            $file_path = $file->storeAs('documents', $file_name.'.'.$file->getClientOriginalExtension());
            $document->path = $file_path;
            $document->save();
        }
        if($package) $document->packages()->attach($package);
        if($request->replaced_id){
            $rdoc = Adocument::find($request->replaced_id);
            if($rdoc) {
               
                $rdoc->status = 'canceled';
                $rdoc->save();
                 $document->relatedDocs()->attach($rdoc, ['type' => 'A']);
            }
        }

        return redirect()->route('archived-documents.edit', $document->id)->with('success', 'Документ успішно створено та збережено в архіві.');
    }
  
    public function edit($id)
    {
         ini_set('memory_limit', '256M'); // або '512M' при потребі
        $id;
         $document = Adocument::findOrFail($id);
        $document->load('packages');
        $package = $document->packages->first();
        $packages = Apackage::all()->keyBy('id')->values();

        $buildings = Building::all()->keyBy('id')->values();
        $docs = Adocument::all()->keyBy('id')->values();

        $parent_type_doc = Type::where('name', 'Вид документа')->value('id');
        $docTypes = $parent_type_doc ? Type::where('parent_id', $parent_type_doc)->get() : collect();

        $parent_type_object = Type::where('name', 'Об\'єкт')->value('id');
        $objectTypes = $parent_type_object ? Type::where('parent_id', $parent_type_object)->get() : collect();
        
        $parent_type_stage = Type::where('name', 'Стадія')->value('id');
        $stageTypes = $parent_type_stage  ? Type::where('parent_id', $parent_type_stage )->get() : collect();

        $parent_type_arhive = Type::where('name', 'Архів')->value('id');
        $archiveTypes = $parent_type_arhive ? Type::where('parent_id', $parent_type_arhive)->get() : collect();

        $parent_type_Developer = Type::where('name', 'Розробник')->value('id');
        $develops = $parent_type_Developer ? Type::where('parent_id', $parent_type_Developer)->get() : collect();

        $parent_type_Contractor = Type::where('name', 'Виконавець')->value('id');
        $Contractors = $parent_type_Contractor ? Type::where('parent_id', $parent_type_Contractor)->get() : collect();
      

        return view('arch.edit', compact('document', 'stageTypes', 'objectTypes', 'packages', 'package', 'docTypes', 'buildings', 'docs', 'develops','Contractors', 'archiveTypes', 'parent_type_doc', 'parent_type_Developer', 'parent_type_Contractor', 'parent_type_object', 'parent_type_arhive'));
    }
    // copy $id
    public function copy($id)
    {
        $document = Adocument::findOrFail($id);
        $package = $document->packages->first();
        $newDocument = $document->replicate();
        // archive_number  = ''
        $newDocument->archive_number = '';
        $newDocument->save();

        if ($package) {
            $newDocument->packages()->attach($package);
        }

        return redirect()->route('archived-documents.edit', $newDocument->id)->with('success', 'Документ успішно скопійовано.');
    }

   public function update(Request $request, $id)
    {
         $document  = Adocument::find($id);
        $document->foreign_name    = $request->input('foreign_name', '');
        $document->national_name   = $request->input('national_name', '');
        $document->reg_date        = $request->input('reg_date', '');        
        $document->pages           = $request->input('pages', 0) ? $request->input('pages', 0) : 0;
        $document->doc_type_id     = $request->input('doc_type_id', null);
        $document->notes           = $request->input('notes', '');
        $document->production_date = $request->input('production_date', '');
        $document->kor             = $request->input('kor', '');
        $document->part            = $request->input('part', '');
        $document->contract        = $request->input('contract', '');
        $document->develop         = $request->input('develop', '');
        $document->object          = $request->input('object', '');
        $document->unit            = $request->input('unit', '');
        $document->stage           = $request->input('stage', '');
        $document->code            = $request->input('code', '');
        $document->inventory       = $request->input('inventory_number', '');   
        $document->archive_number  = $request->input('archive_number', '');
        $document->notes  = $request->input('notes', '');
        $document->path = $request->input('scan', '');
        $document->storage_location= $request->input('location', ''); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

        // Ім'я файлу для збереження
        $file_name = $request->input('unit', '') . "_" . $request->input('object', '') . "_" . $request->input('stage', '') . "_" . $request->input('code', '') . $document->id;

        // Оновлення файлу, якщо завантажено новий
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $file_path = $file->storeAs('documents', $file_name . '.' . $file->getClientOriginalExtension());
            $document->path = $file_path;
        }

        $document->save();

        // Оновлення пакетів (якщо надіслані) package_id
       if ($request->input('package_id')!==0) {
        $package = Apackage::find($request->input('package_id'));
        if ($package) {
            $document->packages()->sync([$package->id]);
        }
       }
       elseif($request->input('package_id')==0){
          if($request->input('package_foreign_name') || $request->input('package_national_name')) {
            $newPackage = Apackage::create([
                'foreign_name'  => $request->input('new_package_foreign_name', ''),
                'national_name' => $request->input('new_package_national_name', ''),
            ]);
            $document->packages()->sync([$newPackage->id]);
            }
       }
       if($request->replaced_id){
            $rdoc = Adocument::find($request->replaced_id);
            if($rdoc) {
               
                $rdoc->status = 'canceled';
                $rdoc->save();
                 $document->relatedDocs()->attach($rdoc, ['type' => 'A']);
            }
        }


        return redirect()->route('archived-documents.show', $document->id)
                        ->with('success', 'Документ успішно оновлено.');
    }


    public function destroy($id)
    {
        $type = Adocument::find($id);
        $type->delete();
         return redirect()->route('archived-documents.index')->with('success', 'Тип успішно видалено.');
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
                            'doc_type_id'      => NULL, // Тип документа
                            'notes'            => NULL,
                            'kor'              => $row[3],
                            'part'             => $row[4],
                            'contract'         => $row[5],
                            'develop'          => "",
                            'object'           => $row[8],
                            'unit'             => $row[9],
                            'stage'            => $row[10],
                            'code'             => $row[12],
                            'inventory'        => $row[13],                                      
                            'archive_number' => "",
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
         ini_set('memory_limit', '256M'); // або '512M' при потребі
        $documents = DampAD::where('damp_date', $date)
       ->with('package')
       
       ->get();
        $packages = DampAP::where('damp_date', $date)->get();

        return view('arch.dumpshow', compact('documents', 'packages'));
    }

    public function new_dump()
    {
        // Логіка для створення нового дампу
        $date = now('Europe/Kiev')->subHours(3)->format('Y-m-d H:i:s');
         ini_set('memory_limit', '256M'); // або '512M' при потребі
        $packages = Apackage::all()->keyBy('id')->values();
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
                'notes' => $doc->notes,
                'pages' => $doc->pages,
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
                'archive_number' => $doc->archive_number,
                'path' => $doc->path,
                'storage_location' => $doc->storage_location,
                'status' => $doc->status? $doc->status : 'active', // якщо статус не вказано, встановлюємо 'active'
                'package_id' => $doc->packages->first()->id ?? null, // якщо пакет не вказано, встановлюємо null
            ]);
        }
           ;
    
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
                'notes' => $doc->notes,
                'pages' => $doc->pages,
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
                'archive_number' => $doc->archive_number,
                'path' => $doc->path,
                'storage_location' => $doc->storage_location,
                'status' => $doc->status ?? 'active',
            ]);

            if ($doc->package_id && Apackage::find($doc->package_id)) {
                $d->packages()->attach($doc->package_id);
            }
        }
    }
    public function settingshow()
    {
        $parent_type_doc = Type::where('name', 'Вид документа')->value('id');
        $docs = $parent_type_doc ? Type::where('parent_id', $parent_type_doc)->get() : collect();
        // смета, акт, довідка, наказ, креслення

        $parent_type_Developer = Type::where('name', 'Розробник')->value('id');
        $Developers = $parent_type_Developer ? Type::where('parent_id', $parent_type_Developer)->get() : collect();

        $parent_type_arhive = Type::where('name', 'Архів')->value('id');
        $archiveTypes = $parent_type_arhive ? Type::where('parent_id', $parent_type_arhive)->get() : collect();

        $parent_type_Contractor = Type::where('name', 'Виконавець')->value('id');
        $Contractors = $parent_type_Contractor ? Type::where('parent_id', $parent_type_Contractor)->get() : collect();

        $parent_type_object = Type::where('name', 'Об\'єкт')->value('id');
        $objects = $parent_type_object ? Type::where('parent_id', $parent_type_object)->get() : collect();

        return view('arch.setshow', compact('docs', 'Developers', 'Contractors', 'objects', 'parent_type_doc', 'parent_type_Developer', 'parent_type_Contractor', 'parent_type_object', 'archiveTypes', 'parent_type_arhive'));
    }

   public function settingupd(Request $request)
    {
        if ($request->input('name') && $request->input('parent_id')) {
            $type = new Type();
            $type->name = $request->input('name');

            // Перевірка на наявність поля 'foreign'
            if ($request->has('foreing')) { 
                $type->foreing = $request->input('foreing'); 
            }

            $type->parent_id = $request->input('parent_id');
            $type->save();
        }
        $model = $type;

        return redirect()->route('archived-documents.set')->with('success', 'Новий тип успішно записано.')
            ->with('model', $model);
    }
    public function analytics()
    {
        $docs = Adocument::all();

        // 1. Скільки документів без української назви (не перекладено)
        $notTranslated = Adocument::whereNull('national_name')
                                ->orWhere('national_name', '')
                                ->count();

        // 2. Скільки мають електронні версії (тобто є path)
        $withElectronic = Adocument::whereNotNull('path')
                                ->where('path', '!=', '')
                                ->count();

        // 3. Скільки не відскановано (немає path)
        $notScanned = Adocument::whereNull('path')
                            ->orWhere('path', '')
                            ->count();

        // 4. Зареєстровані за поточний рік
        $byYear = Adocument::whereYear('reg_date', now()->year)->count();

        // 5. Зареєстровані за поточний місяць
        $byMonth = Adocument::whereYear('reg_date', now()->year)
                            ->whereMonth('reg_date', now()->month)
                            ->count();

        // 6. По виконавцях
        $byExecutors = Adocument::select('kor', DB::raw('count(*) as total'))
                                ->groupBy('kor')
                                ->get();

        // 7. По розробниках
        $byDevelopers = Adocument::select('develop', DB::raw('count(*) as total'))
                                ->groupBy('develop')
                                ->get();

        // 8. По об’єктах
        $byObjects = Adocument::select('object', DB::raw('count(*) as total'))
                            ->groupBy('object')
                            ->get();

        // 9. Зареєстровані з архівними номерами
        $withArchiveNumber = Adocument::whereNotNull('archive_number')
                                    ->where('archive_number', '!=', '')
                                    ->count();

        // 10. Динаміка по місяцях (останні 12)
        $monthlyDynamics = Adocument::select(
                                    DB::raw('YEAR(reg_date) as year'),
                                    DB::raw('MONTH(reg_date) as month'),
                                    DB::raw('COUNT(*) as total')
                                )
                                ->groupBy('year', 'month')
                                ->orderBy('year')
                                ->orderBy('month')
                                ->get();
        // 9. Зареєстровані без архівними номерами
        $withoutArchiveNumber = Adocument::whereNull('archive_number')
                                    ->orWhere('archive_number', '')
                                    ->count();  

        return view('arch.analytics', compact(
            'docs',
            'notTranslated',
            'withElectronic',
            'notScanned',
            'byYear',
            'byMonth',
            'byExecutors',
            'byDevelopers',
            'byObjects',
            'withArchiveNumber',
            'withoutArchiveNumber',
            'monthlyDynamics'
        ));
    }



}
