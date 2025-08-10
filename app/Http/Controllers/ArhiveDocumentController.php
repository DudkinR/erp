<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Adocument;
use App\Models\Apackage;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ArhiveDocumentController extends Controller
{
    public function index()
    {
        $documents = Adocument::with('packages')->get();
        return view('arch.index', compact('documents'));
    }
    // archivedPackages
    public function archivedPackages()
    {
        $packages = Apackage::with('documents')->get();
        return view('arch.indexpackege', compact('packages'));
        
    }
    public function create()
    {
        $packages = Apackage::all();
        return view('arch.create', compact('packages'));
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

    public function edit($id)
    {
        $document = Adocument::findOrFail($id);
        $packages = Apackage::all();
        return view('arch.edit', compact('document', 'packages'));
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
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);
        // Maximum execution time of 360 seconds exceeded
        ini_set('max_execution_time', 360);
       
        $path = $request->file('file')->getRealPath();

        if (($handle = fopen($path, 'r')) !== false) {
            $header = fgetcsv($handle, 1000, ','); // пропускаємо заголовок

            DB::beginTransaction();
            try {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    // Якщо треба — перетворюємо з Windows-1251
                 // return $row ;

                    // 1. Пакет
                    $package = Apackage::firstOrCreate(
                        ['id' => $row[0]], // зберігаємо ID з CSV
                        [
                            'foreign_name'  => $row[6], // Наименование проекта
                            'national_name' => "",
                        ]
                    );

                    // 2. Документ
                    $document = Adocument::firstOrCreate(
                        ['id' => $row[1]], // ID документа з CSV
                        [
                            'foreign_name'     => $row[11], // NAME документа
                            'national_name'    => "",
                            'reg_date'         => $this->parseDate($row[2]),
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
        } catch (\Exception $e) {
            \Log::error('PDF generation failed: '.$e->getMessage());
            return response()->json(['message' => 'PDF generation failed', 'error' => $e->getMessage()], 500);
        }

        return $pdf->download('archive.pdf');
    }





     public  $rus_words = [
        'Альбом',
        'Анализ',
        'Ведомость деталей (сборочных единиц) к типовому (групповому) технологическому процессу(операции)',
        'Ведомость деталей, изделий, приборов',
        'Ведомость материалов',
        'Ведомость объемов строительных и монтажных работ',
        'Ведомость работы',
        'Генплан (включая сводные и ситуационные планы, горизонтальные и вертикальные планировки и др..)',
        'Детали закладные',
        'Дополнительные материалы по анализу безопасности',
        'Журнал силовых и контрольных кабелей',
        'Задание заводу на оборудование',
        'Задание техническое',
        'Записка',
        'Записка пояснительная к Проекту',
        'Записка пояснительная к разработанной проектной документации по модернизации/реконструкции',
        'Инструкция',
        'Инструкция технологическая',
        'Исполнительная документация',
        'Карта технологическая',
        'Классификация',
        'Материалы',
        'Методика (техническая) выполнения работ (расчетов)',
        'Монтажный чертеж',
        'Обоснование (Обосновывающие материалы)',
        'Общие виды',
        'Окончательный отчет по анализу безопасности',
        'Описание',
        'Описание техническое по эксплуатации',
        'Опросный лист',
        'Отчет',
        'Отчет о расследовании нарушения в работе АС',
        'Отчет о энергетическом пуске',
        'Отчет по анализу безопасности',
        'Паспорт',
        'Перечень',
        'Перечень материалов по техническому обоснованию безопасности',
        'План аварийный ( в случае радиационной аварии)',
        'План мероприятий при нарушений нормальных условий эксплуатации установок по обращению с РАО',
        'Положение по выполнению работ',
        'Предписание',
        'Программа',
        'Программа комплексная гидравлических (пневматических) испытаний',
        'Программа технического обслуживания СВБ',
        'Проект (энергоблока)',
        'Проект изоляции и/или антикоррозионной защиты',
        'Проект производства работ для строительства',
        'Проект рабочий',
        'Проект технический',
        'Проект типовой',
        'Расчет гидравлический (тепло-гидравлический)',
        'Расчет на все виды воздействия',
        'Расчет на прочность (сейсмостойкость, устойчивость)',
        'Расчет электрический',
        'Регламент',
        'Реестр',
        'Руководство по автоматизированным системам управления (руководство администратора программного обеспечения, программиста, пользователя)',
        'Сборочные',
        'Смета',
        'Спецификация'
    ];
    public $ukr_words = [
        'Альбом',
        'Аналіз',
        'Відомість деталей (складальних одиниць) до типового (групового) технологічного процесу (операції)',
        'Відомість деталей, виробів, приладів',
        'Відомість матеріалів',
        'Відомість обсягів будівельних і монтажних робіт',
        'Відомість роботи',
        'Генплан (включаючи зведені та ситуаційні плани, горизонтальні та вертикальні планування тощо)',
        'Деталі закладні',
        'Додаткові матеріали з аналізу безпеки',
        'Журнал силових і контрольних кабелів',
        'Завдання заводу на обладнання',
        'Завдання технічне',
        'Записка',
        'Записка пояснювальна до Проекту',
        'Записка пояснювальна до розробленої проектної документації з модернізації/реконструкції',
        'Інструкція',
        'Інструкція технологічна',
        'Виконавча документація',
        'Карта технологічна',
        'Класифікація',
        'Матеріали',
        'Методика (технічна) виконання робіт (розрахунків)',
        'Монтажний креслення',
        'Обґрунтування (Обґрунтовуючі матеріали)',
        'Загальні види',
        'Остаточний звіт з аналізу безпеки',
        'Опис',
        'Опис технічне з експлуатації',
        'Опросний лист',
        'Звіт',
        'Звіт про розслідування порушення в роботі АС',
        'Звіт про енергетичний пуск',
        'Звіт з аналізу безпеки',
        'Паспорт',
        'Перелік',
        'Перелік матеріалів з технічного обґрунтування безпеки',
        'План аварійний (у разі радіаційної аварії)',
        'План заходів при порушеннях нормальних умов експлуатації установок з поводження з РАО',
        'Положення з виконання робіт',
        'Припис',
        'Програма',
        'Програма комплексна гідравлічних (пневматичних) випробувань',
        'Програма технічного обслуговування СВБ',
        'Проект (енергоблоку)',
        'Проект ізоляції та/або антикорозійного захисту',
        'Проект виробництва робіт для будівництва',
        'Проект робочий',
        'Проект технічний',
        'Проект типовий',
        'Розрахунок гідравлічний (тепло-гідравлічний)',
        'Розрахунок на всі види впливу',
        'Розрахунок на міцність (сейсмостійкість, стійкість)',
        'Розрахунок електричний',
        'Регламент',
        'Реєстр',
        'Посібник з автоматизованим системам управління (посібник адміністратора програмного забезпечення, програміста, користувача)',
        'Складальні',
        'Кошторис',
        'Специфікація'
    ];

}
