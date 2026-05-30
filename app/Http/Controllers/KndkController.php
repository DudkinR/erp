<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kndk;
use App\Models\Process;
use App\Models\Document;
use App\Models\Division;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
class KndkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
         $kndks = Kndk::orderBy('class', 'asc')
    ->orderByRaw('CASE WHEN subclass IS NULL THEN 0 ELSE 1 END')
    ->orderBy('subclass', 'asc')
    ->orderByRaw('CASE WHEN `group` IS NULL THEN 0 ELSE 1 END')
    ->orderBy('group', 'asc')
    ->withCount('documents')
    ->get();
        return view('kndks.index', compact('kndks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createprocess()
    {
        //
        //return view('kndks.create'); все додано
         $kndks = Kndk::orderBy('class', 'asc')
        ->orderByRaw('CASE WHEN subclass IS NULL THEN 0 ELSE 1 END')
        ->orderBy('subclass', 'asc')
        ->orderByRaw('CASE WHEN `group` IS NULL THEN 0 ELSE 1 END')
        ->orderBy('group', 'asc')
        ->withCount('documents')
        ->get();
        $rootDivisions = Division::where('parent_id', 0)->orderBy('name', 'asc')->get();
        $positions = Position::orderBy('id', 'asc')->take(19)->get();
        return view('kndks.createprocess', compact('kndks','rootDivisions','positions')); 
    }
    
    public function create()
    {        
       return view('kndks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(Request $request)
{
    // 1. Валідація вхідних даних
    $validated = $request->validate([
        'name'               => 'nullable|string|max:255',
        'process_type'       => 'required_with:name|nullable|string|in:inputs,resources,outputs,tasks,results,performance',
        'description'        => 'nullable|string',
        'kndk_ids'           => 'required|array|min:1',
        'kndk_ids.*'         => 'exists:kndks,id', // Виправлено назву таблиці на 'kndk'
        'division_ids'       => 'nullable|array',
        'division_ids.*'     => 'exists:division,id',
        'position_own_ids'   => 'nullable|array', // Власники процесу
        'position_own_ids.*' => 'exists:positions,id',
        'position_ids'       => 'nullable|array', // Учасники процесу
        'position_ids.*'     => 'exists:positions,id',
    ]);

    $message = '';

    // СЦЕНАРІЙ А: Заповнено назву процесу -> Створюємо процес
    if (!empty($validated['name'])) {
        
        // 2. Створення базового запису процесу (включаючи тип)
        $process = Process::create([
            'name'         => $validated['name'],
            'type' => $validated['process_type'],
            'description'  => $validated['description'],
        ]);

        // 3. Прив'язка КНДК до створеного Процесу (Many-to-Many)
        $process->kndks()->attach($validated['kndk_ids']);
        
        $message = 'Процес успішно створено та пов\'язано з КНДК. ';
    }

    // 4. Цикл для перебору всіх обраних КНДК та прив'язки підрозділів і посад
    foreach ($validated['kndk_ids'] as $kndkId) {
        $kndk = Kndk::find($kndkId);

        if ($kndk) {
            // Прив'язка підрозділів до конкретного КНДК
            if (!empty($validated['division_ids'])) {
                $kndk->divisions()->syncWithoutDetaching($validated['division_ids']);
            }

            // ТУТ ЗВ'ЯЗУЄМО ВЛАСНИКІВ через ваш зв'язок responsibles()
            if (!empty($validated['position_own_ids'])) {
                $kndk->responsibles()->syncWithoutDetaching($validated['position_own_ids']);
            }

            // ТУТ ЗВ'ЯЗУЄМО УЧАСНИКІВ (якщо у КНДК для них окремий зв'язок, наприклад, positions())
            if (!empty($validated['position_ids'])) {
                $kndk->positions()->syncWithoutDetaching($validated['position_ids']);
            }
        }
    }

    // Формування фінального тексту сповіщення
    if (empty($validated['name'])) {
        $message = 'Зв\'язки підрозділів та посад з обраними КНДК успішно оновлено!';
    } else {
        $message .= 'Підрозділи та посади також прив\'язані до КНДК.';
    }

    // 5. Перенаправлення користувача назад
    return redirect()
        ->route('kndks.createprocess')
        ->with('success', $message);
}



    public function pairs($divisions_id, $positions_id)
    {
        $divisions = Division::whereIn('id', $divisions_id)->get();
        $positions = Position::whereIn('id', $positions_id)->get();
        // find pairs  divisions_positions
        $pairs = [];
        foreach($divisions as $division){
            foreach($positions as $position){
                if($division->positions->contains($position->id)){
                    $pairs[] = [$division->id , $position->id];
                }
            }
        } 
        return $pairs;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Завантажуємо КНДК разом із прив'язаними документами
        $item = Kndk::with(['documents', 'processes', 'divisions', 'responsibles', 'positions'])->findOrFail($id);
        $organizations = $item->documents
        ->pluck('organization') // Беремо лише стовпчик organization [^14]
        ->filter()              // Видаляємо null або порожні рядки (якщо такі є)
        ->unique()              // Залишаємо лише унікальні назви [15]
        ->values()              // Скидаємо індекси масиву до 0, 1, 2...
        ->toArray();            // Перетворюємо у звичайний PHP масив
        // Ваша поточна логіка обробки заголовка та опису
        $title = Str::of($item->name)->explode("\n")->first();
        $safeDescription = $item->name; // Або ваша логіка
        $linkedItems = []; // Ваша логіка пошуку відповідностей
        return view('kndks.show', compact('item', 'title', 'safeDescription', 'linkedItems','organizations'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $kndk = Kndk::with(['responsibles', 'positions', 'divisions'])->find($id);
         $rootDivisions = Division::where('parent_id', 0)->orderBy('name', 'asc')->get();
        $positions = Position::orderBy('id', 'asc')->take(19)->get();
        return view('kndks.edit', compact('kndk','rootDivisions','positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'class'       => 'required|integer',
        'subclass'    => 'nullable|string|size:2',
        'group'       => 'nullable|string',
        'full_code'   => 'required|string' ,
        'name'        => 'required|string', 
        'division_ids'       => 'nullable|array',
        'division_ids.*'     => 'exists:division,id',
        'position_own_ids'   => 'nullable|array', // Власники процесу
        'position_own_ids.*' => 'exists:positions,id',
        'position_ids'       => 'nullable|array', // Учасники процесу
        'position_ids.*'     => 'exists:positions,id',
    ], [
        'full_code.unique' => 'Елемент із цифровим кодом :input вже існує в класифікаторі!',
    ]);

    $kndk=Kndk::find($id);

    // Присвоюємо значення властивостям об'єкта моделі
    $kndk->class = $validated['class'];
    $kndk->name = $validated['name'];
    $kndk->full_code = $validated['full_code'];


    // Обов'язково оновлюємо subclass та group відповідно до рівня
    if ($kndk->level === 1) {
        $kndk->subclass = null;
        $kndk->group = null;
    } elseif ($kndk->level === 2) {
        $kndk->subclass = $validated['subclass'];
        $kndk->group = null;
    } elseif ($kndk->level === 3) {
        $kndk->subclass = $validated['subclass'];
        $kndk->group = $validated['group'];
    }

    // Зберігаємо змінений об'єкт у базу даних
    $kndk->save();
     // Прив'язка підрозділів до конкретного КНДК
            if (!empty($validated['division_ids'])) {
                $kndk->divisions()->syncWithoutDetaching($validated['division_ids']);
            }

            // ТУТ ЗВ'ЯЗУЄМО ВЛАСНИКІВ через ваш зв'язок responsibles()
            if (!empty($validated['position_own_ids'])) {
                $kndk->responsibles()->syncWithoutDetaching($validated['position_own_ids']);
            }

            // ТУТ ЗВ'ЯЗУЄМО УЧАСНИКІВ (якщо у КНДК для них окремий зв'язок, наприклад, positions())
            if (!empty($validated['position_ids'])) {
                $kndk->positions()->syncWithoutDetaching($validated['position_ids']);
            }
    


    return redirect()->route('kndks.index')->with('success', 'Елемент класифікатора успішно оновлено!');
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // detach
        $kndk = Kndk::find($id);
        $kndk->delete();
        return redirect()->route('kndks.index');
        
    }

    
    public function showImportPage($id)
    {
        // Знаходимо конкретний КНДК або видаємо 404, якщо не знайдено
        $kndk = Kndk::findOrFail($id);

        return view('kndks.import', compact('kndk'));
    }
    
    public function importCsvDocs(Request $request, $id)
    {
            $request->validate([
                'import_file' => 'required|file|mimes:csv,txt',
            ]);

            $file = $request->file('import_file');
            $handle = fopen($file->getRealPath(), 'r');
            
            $previewData = [];
            $notInDatabase = [];
            $counter = 0;

            // Кешуємо перші 19 посад один раз за допомогою короткого імені
            $first19PositionIds = Position::orderBy('id', 'asc')
            ->skip(1)
            ->take(18)
            ->pluck('id')
            ->toArray();

            while (($row = fgetcsv($handle, 1000, ";")) !== false && $counter < 1000) {
            
            $processedRow = array_map(function ($value) {
                if ($value === null || $value === '') return '';
                if (!mb_check_encoding($value, 'UTF-8')) {
                    return mb_convert_encoding($value, 'UTF-8', 'Windows-1251');
                }
                return trim(preg_replace('/[\x{00A0}\s]+/u', ' ', $value));
            }, $row);

            if (empty($processedRow[0]) && empty($processedRow[1]) && empty($processedRow[2])) {
                continue;
            }

            // Пошук моделі за коротким іменем Kndk
            $kndk_full_code = $processedRow[2] ?? null;
            $kndk = Kndk::where('full_code', $kndk_full_code)->first();

            if (!$kndk) {
                $notInDatabase['kndk'][] = $kndk_full_code;
                $previewData[] = $processedRow;
                $counter++;
                continue;
            }

            $responsiblesIds = [];
            $positionsIds = [];
            $divisionsIds = [];

            // Обробка ВЛАСНИКІВ
            if (!empty($processedRow[0])) {
                $ownerAbv = mb_strtolower($processedRow[0]);
                
                // Використовуємо коротке ім'я Position
                $posId = Position::whereRaw('LOWER(abv) = ?', [$ownerAbv])->value('id');
                
                if ($posId) {
                    $responsiblesIds[] = $posId;
                } else {
                    $notInDatabase['positions_owners'][] = $processedRow[0];
                }
            }

            // 3. Обробка ВІДПОВІДАЛЬНИХ
            if (!empty($processedRow[1])) {
                $responAbvs = array_filter(array_map('trim', explode(',', $processedRow[1])));
                
                // ВИПРАВЛЕНО: Безпечне переведення масиву кирилиці в нижній регістр
                $lowerResponAbvs = array_map(function($value) {
                    return mb_strtolower($value, 'UTF-8');
                }, $responAbvs);

                // Масовий запит до positions
                $foundPositions = Position::whereIn(DB::raw('LOWER(abv)'), $lowerResponAbvs)
                    ->pluck('id', 'abv')
                    ->toArray();

                // Масовий запит до divisions
                $foundDivisions = Division::whereIn(DB::raw('LOWER(abv)'), $lowerResponAbvs)
                    ->pluck('id', 'abv')
                    ->toArray();

                $positionsIds = array_values($foundPositions);
                $divisionsIds = array_values($foundDivisions);

                foreach ($responAbvs as $abv) {
                    $lAbv = mb_strtolower($abv, 'UTF-8');
                    // Звіряємо ключі знайдених елементів
                    $foundPosKeys = array_map('mb_strtolower', array_keys($foundPositions));
                    $foundDivKeys = array_map('mb_strtolower', array_keys($foundDivisions));

                    if (!in_array($lAbv, $foundPosKeys) && !in_array($lAbv, $foundDivKeys)) {
                        $notInDatabase['responsibles_not_found'][] = $abv;
                    }
                }

                if (in_array('квлу', $lowerResponAbvs)) {
                    $positionsIds = array_merge($positionsIds, $first19PositionIds);
                }    
            }    

            // Синхронізація
            $kndk->responsibles()->sync(array_unique($responsiblesIds));
            $kndk->positions()->sync(array_unique($positionsIds));
            $kndk->divisions()->sync(array_unique($divisionsIds));

            $previewData[] = $processedRow;
            $counter++;
        }
        
        fclose($handle);

        return response()->json([
            'preview_data' => $previewData,
            'not_found_in_db' => [
                'kndk_codes' => isset($notInDatabase['kndk']) ? array_unique($notInDatabase['kndk']) : [],
                'owner_abbreviations' => isset($notInDatabase['positions_owners']) ? array_unique($notInDatabase['positions_owners']) : [],
                'responsible_abbreviations' => isset($notInDatabase['responsibles_not_found']) ? array_unique($notInDatabase['responsibles_not_found']) : []
            ]
        ], 200, [], JSON_UNESCAPED_UNICODE); 
    }




    /*
   public function importCsvDocs(Request $request, $id)
    {
        $kndk = Kndk::findOrFail($id);

        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('import_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        fgetcsv($handle, 1000, ";"); 

        $attachedCount = 0;
        // Масив для накопичення ID документів, які треба прив'язати до Kndk
        $documentIdsToSync = [];

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                
                $row = array_map(function($value) {
                    if ($value === null) return null;
                    if (!mb_check_encoding($value, 'UTF-8')) {
                        return mb_convert_encoding($value, 'UTF-8', 'Windows-1251');
                    }
                    return $value;
                }, $row);

                $invNo = isset($row[5]) ? trim($row[5]) : null;

                if (!$invNo || $invNo === '-') {
                    continue; 
                }

                // updateOrCreate гарантує, що документ в базі буде лише один
                $document = Document::updateOrCreate(
                    ['inv_no' => $invNo], 
                    [
                        'doc_type'          => $row[2] ?? null,
                        'code'              => $row[3] ?? null,
                        'organization'      => $row[4] ?? null,
                        'short_content'     => $row[6] ?? null,
                        'date_reg'          => $this->parseDate($row[7] ?? null),
                        'date_start'        => $this->parseDate($row[8] ?? null),
                        'date_end'          => $this->parseDate($row[9] ?? null),
                        'distribution'      => $row[10] ?? null,
                        'replaced_content'  => $row[11] ?? null,
                        'replaced_by'       => $row[12] ?? null,
                        'change_no'         => $row[13] ?? null,
                        'page_count'        => isset($row[14]) && is_numeric($row[14]) ? (int)$row[14] : null,
                        'note'              => $row[15] ?? null,
                        'storage_location'  => $row[16] ?? null,
                        'registration_date' => $this->parseDate($row[17] ?? null),
                        'is_cancelled'      => in_array(trim($row[18] ?? ''), ['Т', 'Да', '1', 'true']),
                        'cancellation_date' => $this->parseDate($row[19] ?? null),
                        'is_reissued'       => in_array(trim($row[20] ?? ''), ['Т', 'Да', '1', 'true']),
                        'author'            => $row[21] ?? null,
                        'approved_by'       => $row[22] ?? null,
                        'project'           => $row[23] ?? null,
                    ]
                );

                // Важливо: додаємо саме первинний ID моделі (або inv_no, якщо він є primary key в міграції)
                $documentIdsToSync[] = $document->getKey(); 
                $attachedCount++;
            }

            fclose($handle);

            // Прив'язуємо всі знайдені/створені документи за один SQL-запит
            // Другий параметр false означає "не видаляти старі зв'язки" (аналог syncWithoutDetaching)
            if (!empty($documentIdsToSync)) {
                $kndk->documents()->sync($documentIdsToSync, false);
            }

            DB::commit();

            return redirect()
                ->route('kndks.importPage', $kndk->id)
                ->with('success', "Дані успішно імпортовано! Оброблено документів: {$attachedCount}");

        } catch (\Exception $e) {
            DB::rollBack();
            if (is_resource($handle)) {
                fclose($handle);
            }

            return redirect()
                ->back()
                ->with('error', "Помилка при імпорті: " . $e->getMessage());
        }
    }
        */


    // Допоміжний метод залишається без змін (оптимізовано trim)
    private function parseDate($dateString)
    {
        if (empty($dateString) || trim($dateString) === '-') {
            return null;
        }
        try {
            return Carbon::createFromFormat('d.m.Y', trim($dateString))->format('Y-m-d');
        } catch (\Exception $e) {
            return null; 
        }
    }
    public function searchPage()
    {
        return view('kndks.search'); // Назва вашого blade-файлу (наприклад, resources/views/kndks/search.blade.php)
    }

public function search(Request $request)
{
    $text = trim($request->get('query'));

    if (empty($text)) {
        return response()->json([]);
    }

    $lowerText = mb_strtolower($text, 'UTF-8');

    // 1. Очищення від стоп-слів
    $stopWords = [
        'і', 'й', 'та', 'але', 'чи', 'або', 'що', 'як', 'це', 'про', 'на', 'в', 'у', 'за', 'до', 'для', 'від', 
        'під', 'над', 'перед', 'по', 'через', 'при', 'біля', 'з', 'із', 'зі', 'між', 'без', 'якщо', 'тому', 
        'все', 'всі', 'його', 'її', 'їх', 'процес', 'документ', 'інструкція', 'положення'
    ];

    // Визначаємо "важкі" технічні слова, які мають критичне значення
    $highPriorityKeywords = [
    // Ядерна та радіаційна безпека
    'радіаційн', 'ядерн', 'безпек', 'випромінюв', 'ізотоп', 'активн', 'дозиметр', 'дезактивац', 
    'радіоактивн', 'рав', 'яв', 'свпп', 'гермозон', 'біозахист', 'кюрі', 'рентген', 'моніторинг',
    
    // Експлуатація та обладнання реакторного/турбінного цехів
    'реактор', 'турбін', 'енергоблок', 'гцн', 'вввр', 'парогенератор', 'конденсатор', 'компресор',
    'насос', 'арматур', 'клапан', 'трубопровід', 'маніпулятор', 'генератор', 'трансформатор', 
    'електрообладнан', 'щит', 'бщу', 'рщу', 'свпк', 'асу', 'акнп', 'квп', 'автоматик',
    
    // Технічне обслуговування, ремонт та контроль (ТОіР)
    'ремонт', 'модернізац', 'реконструкц', 'дефект', 'зварюван', 'наплавлен', 'контрол', 'діагностик', 
    'неруйнівн', 'випробуван', 'пусконалагодж', 'техогляд', 'експертиз', 'ресурс', 'продовжен',
    
    // Аварійна готовність та спецконтексти
    'аварі', 'критері', 'класифікац', 'інцидент', 'відмов', 'спрацюван', 'захист', 'блокуван', 
    'локалізац', 'ліквідац', 'ситуац', 'план', 'плрп', 'запроектн', 'проектн', 'герметичн',
    
    // Хімія та паливо
    'палив', 'твзел', 'ввп', 'уран', 'плутоній', 'водно-хімічн', 'вхр', 'фільтр', 'корозі', 'продувк',
// Управління та організація
    'управлінн', 'організац', 'системи', 'структур', 'наказ', 'розпоряджен', 'регламент', 'процедур',
    'керівництв', 'адміністрац', 'менеджмент', 'плануванн', 'звітн', 'аудит', 'перевірк', 'контрол',
    
    // Документообіг та діловодство
    'документ', 'архів', 'реєстрац', 'кореспонденц', 'лист', 'протокол', 'акт', 'довідк', 'договір',
    'контракт', 'угод', 'положенн', 'інструкці', 'правил', 'супровід', 'погодженн', 'затвердженн',
    
    // Кадри, персонал та навчання
    'персонал', 'кадр', 'навчанн', 'підготовк', 'кваліфікац', 'атестац', 'тренажер', 'утц', 'посад',
    'інструктаж', 'штатн', 'розпис', 'трудов', 'дисциплін', 'резерв', 'відпустк', 'відрядженн',
    
    // Фінанси, економіка та закупівлі
    'бюджет', 'фінанс', 'економік', 'закупівл', 'тендер', 'прозорро', 'кошторис', 'бухгалтері', 'оплат',
    'рахунок', 'аудит', 'поставк', 'матеріал', 'склад', 'логістик', 'дпц', 'цін', 'вартіст',
    
    // Правова, юридична діяльність та охорона
    'юрид', 'право', 'закон', 'суд', 'ліценз', 'дозвіл', 'регулятор', 'держатомрегулюван', 'дiару', 
    'охорон', 'перепустк', 'режим', 'сб', 'загін', 'вохр', 'таємн', 'конфіденційн'

    ];

    preg_match_all('/[a-zA-Zа-яієїґА-ЯІЄЇҐ0-9\.\-]+/u', $lowerText, $matches);
    $rawWords = $matches[0] ?? [];

    $searchTerms = [];
    foreach ($rawWords as $word) {
        $word = trim($word);
        if (in_array($word, $stopWords) || (mb_strlen($word, 'UTF-8') < 2 && !is_numeric($word))) {
            continue;
        }

        // Український стемінг (обрізання закінчень)
        $stemmed = preg_replace('/(ий|ій|ою|ею|и|і|е|а|я|у|ю|ом|ем|ів|ам|ям|ами|ями|их|ові|еві|ення|енню|енням|иця|иці|ицю|ями|ях)$/u', '', $word);
        
        if (mb_strlen($stemmed, 'UTF-8') >= 2 || is_numeric($stemmed)) {
            $searchTerms[] = $stemmed;
        } else {
            $searchTerms[] = $word;
        }
    }

    if (empty($searchTerms)) {
        return response()->json([]);
    }

    $searchTerms = array_unique($searchTerms);

    // 2. Вибірка з бази: завантажуємо КНДК, що містять ХОЧА Б ОДИН корінь слова
    $kndks = Kndk::with(['documents', 'responsibles', 'positions', 'divisions'])
        ->where(function($mainQuery) use ($searchTerms) {
            foreach ($searchTerms as $term) {
                $mainQuery->orWhere('full_code', 'LIKE', "%{$term}%")
                    ->orWhere('name', 'LIKE', "%{$term}%")
                    ->orWhereHas('documents', function($q) use ($term) {
                        $q->where('short_content', 'LIKE', "%{$term}%")->orWhere('organization', 'LIKE', "%{$term}%");
                    })
                    ->orWhereHas('responsibles', function($q) use ($term) { $q->where('name', 'LIKE', "%{$term}%")->orWhere('abv', 'LIKE', "%{$term}%"); })
                    ->orWhereHas('positions', function($q) use ($term) { $q->where('name', 'LIKE', "%{$term}%")->orWhere('abv', 'LIKE', "%{$term}%"); })
                    ->orWhereHas('divisions', function($q) use ($term) { $q->where('name', 'LIKE', "%{$term}%")->orWhere('abv', 'LIKE', "%{$term}%"); });
            }
        })
        ->get();

    $sortedResults = [];

    // 3. Розрахунок РЕЛЕВАНТНОСТІ за допомогою вагових коефіцієнтів
    foreach ($kndks as $kndk) {
        $score = 0;

        // Витягуємо тексти окремо за рівнями важливості
        $kndkMainText = mb_strtolower($kndk->full_code . ' ' . $kndk->name, 'UTF-8');
        
        $documentsText = '';
        foreach ($kndk->documents as $doc) {
            $documentsText .= ' ' . mb_strtolower($doc->short_content . ' ' . $doc->organization, 'UTF-8');
        }

        $otherRelationsText = '';
        foreach ([$kndk->responsibles, $kndk->positions, $kndk->divisions] as $relationGroup) {
            foreach ($relationGroup as $itemRow) {
                $otherRelationsText .= ' ' . mb_strtolower($itemRow->name . ' ' . $itemRow->abv, 'UTF-8');
            }
        }

        // --- ПЕРЕВІРКА 1: Точні збіги фрази (Дає величезну перевагу) ---
        // Якщо в назві КНДК є точна фраза типу "радіаційної безпеки"
        if (mb_strpos($kndkMainText, 'радіаційн', 0, 'UTF-8') !== false && mb_strpos($kndkMainText, 'безпек', 0, 'UTF-8') !== false) {
            $score += 150; 
        }
        if (mb_strpos($kndkMainText, 'аварі', 0, 'UTF-8') !== false) {
            $score += 100;
        }

        // --- ПЕРЕВІРКА 2: Пословний аналіз із вагою полів ---
        foreach ($searchTerms as $term) {
            // Визначаємо множник для важливих слів (радіаційн, аварі = х15, забезпечення = х1)
            $isHighPriority = false;
            foreach ($highPriorityKeywords as $hpKey) {
                if (mb_strpos($term, $hpKey, 0, 'UTF-8') !== false) {
                    $isHighPriority = true;
                    break;
                }
            }
            $wordWeight = $isHighPriority ? 15 : 1;

            // Збіг у назві або коді КНДК (Найвищий пріоритет)
            if (mb_strpos($kndkMainText, $term, 0, 'UTF-8') !== false) {
                $score += (20 * $wordWeight);
            }
            // Збіг у тексті документів (Середній пріоритет)
            if (mb_strpos($documentsText, $term, 0, 'UTF-8') !== false) {
                $score += (5 * $wordWeight);
            }
            // Збіг у назвах підрозділів/посад (Низький пріоритет)
            if (mb_strpos($otherRelationsText, $term, 0, 'UTF-8') !== false) {
                $score += (2 * $wordWeight);
            }
        }

        if ($score > 0) {
            $sortedResults[] = [
                'id' => $kndk->id,
                'full_code' => $kndk->full_code,
                'name' => $kndk->name ?? 'Процес КНДК',
                'score' => $score // Сортуємо за балами релевантності
            ];
        }
    }

    // Сортування: КНДК з найбільшою кількістю балів піднімаються нагору
    usort($sortedResults, function($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    $finalResults = array_slice($sortedResults, 0, 20);

    return response()->json($finalResults);
}

}
