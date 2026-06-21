<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kndk;
use App\Models\Process;
use App\Models\Document;
use App\Models\Division;
use App\Models\Position;
use App\Models\Keyword;
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
        $Bosspositions = Position::orderBy('id', 'asc')->take(19)->get();
        $positions = Position::orderBy('id', 'asc')->get();
        return view('kndks.createprocess', compact('kndks','rootDivisions','positions','Bosspositions')); 
    }
    
    public function create()
    {        
        // Беремо останній створений запис із бази
        $lastInserted = Kndk::latest('id')->first();

        return view('kndks.create', compact('lastInserted'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Валідація вхідних даних
        $validated = $request->validate([
            'level_toggle' => 'required|in:1,2,3',
            'class'        => 'required|string',
            'subclass'     => 'nullable|string',
            'group'        => 'nullable|string',
            'full_code'    => 'required|string',
            'name'         => 'required|string|max:255',
            'object_type'  => 'required|string|max:255',
        ]);

        // 2. Очищення полів залежно від обраного рівня
        $level = (int) $validated['level_toggle'];
        
        if ($level === 1) {
            $validated['subclass'] = null;
            $validated['group'] = null;
        } elseif ($level === 2) {
            $validated['group'] = null;
        }

        // 3. Запис у базу даних (токен автоматично ігнорується, бо його немає у $fillable)
        $kndk = Kndk::create($validated);
          // Повертаємося назад на сторінку створення із флеш-повідомленням у сесії
        return redirect()->route('kndks.create')->with('success', 'Елемент успішно додано до класифікатора!');
    }

    public function store_pocedure(Request $request)
    {
           // return $request;
        // 1. Валідація вхідних даних (Виправлено назви таблиць у базі danych)
        $validated = $request->validate([
            'name'               => 'nullable|string',
            'process_type' => [
                'nullable',
                'required_with:name',
                'string',
                'in:inputs,resources,outputs,tasks,results,performance,corporate_requirements'
            ],
            'description'        => 'nullable|string',
            'kndk_ids'           => 'required|array|min:1',
            'kndk_ids.*'         => 'exists:kndks,id', // Зазвичай у Laravel plural: kndks
            'division_ids'       => 'nullable|array',
            'division_ids.*'     => 'exists:division,id', // ВИПРАВЛЕНО: назва таблиці 'division' замість 'division'
            'position_own_ids'   => 'nullable|array', 
            'position_own_ids.*' => 'exists:positions,id',
            'position_ids'       => 'nullable|array', 
            'position_ids.*'     => 'exists:positions,id',
            'document_id' => 'nullable|string',
            'search_text' => 'nullable|string',
            'process_id' => 'nullable|exists:processes,id',

        ]);

        $message = '';

        // СЦЕНАРІЙ А: Заповнено назву процесу -> Працюємо з Процесом (Функцією)
        if (!empty($validated['name'])) {
            
            // 1. Очищаємо оригінальні дані від крайових пробілів (для запису)
            $originalName = trim($validated['name']);
            $trimmedDescription = !empty(trim($validated['description'] ?? '')) ? trim($validated['description']) : null;

            // 2. Функція для створення «пошукового зліпка» тексту (лише для порівняння)
            $cleanText = function($text) {
                $text = mb_strtolower($text, 'UTF-8'); // Нижній регістр з підтримкою кирилиці
                // Видаляємо крапки, коми, пробіли, тире та інші знаки
                return preg_replace('/[\s\.,\-_–—!?;"\'«»()]/u', '', $text);
            };

            $searchName = $cleanText($originalName);

          // 1. Спочатку перевіряємо, чи передано конкретний id процесу для редагування/апдейту
            if (!empty($validated['process_id'])) {
                $process = Process::find($validated['process_id']);
                
                if ($process) {
                    // Оновлюємо опис (за потреби сюди можна додати й 'name' => $validated['name'])
                    $process->update([
                         'name'        => $originalName, // Зберігає великі літери, пробіли та крапки
                        'type'        => $validated['process_type'],
                        'description' => $trimmedDescription
                    ]);
                    $process->wasRecentlyCreated = false;
                }
            }

            // 2. Якщо id не передано, виконуємо ваш стандартний RAW-пошук за нормалізованим іменем
            if (!isset($process)) {
                    // 3. Шукаємо в базі через RAW-запит, очищуючи кожне ім'я з бази «на льоту»
                    $process = Process::where('type', $validated['process_type'])
                        ->whereRaw("
                            LOWER(
                                REPLACE(
                                REPLACE(
                                REPLACE(
                                REPLACE(
                                REPLACE(
                                REPLACE(name, ' ', ''),
                                '.', ''),
                                ',', ''),
                                '-', ''),
                                '(', ''),
                                ')', '')
                            ) = ?
                        ", [$searchName])
                        ->first();

                    // 4. Логіка: знайдено (перезаписуємо опис) чи ні (створюємо новий з ОРИГІНАЛЬНИМ ім'ям)
                    if ($process) {
                        // Знайшли схожий! Перезаписуємо опис (назва з великими літерами в базі лишається БЕЗ змін)
                        $process->update([
                            'name'        => $originalName, // Зберігає великі літери, пробіли та крапки
                            'type'        => $validated['process_type'],
                            'description' => $trimmedDescription
                        ]);
                        // Тимчасово ставимо прапорець для сумісності з вашим подальшим кодом
                        $process->wasRecentlyCreated = false; 
                    } else {
                    // Нічого схожого немає — створюємо новий рядок з оригінальним гарним текстом
                    $process = Process::create([
                        'name'        => $originalName, // Зберігає великі літери, пробіли та крапки
                        'type'        => $validated['process_type'],
                        'description' => $trimmedDescription,
                    ]);
                    $process->wasRecentlyCreated = true;
                }
            }
            if (!$process->wasRecentlyCreated && !empty($validated['description'])) {
                $process->update(['description' => $validated['description']]);
            }

            // 3. Прив'язка КНДК до Процесу
            $process->kndks()->syncWithoutDetaching($validated['kndk_ids']);
            $process->documents()->syncWithoutDetaching($validated['document_id'] ?? []);

           // МОДЕРНІЗАЦІЯ: Прив'язуємо підрозділи безпосередньо до Процесу
            if ($validated['process_type'] === 'corporate_requirements') {
                // Якщо це загальнокорпоративні вимоги — беремо ID абсолютно всіх підрозділів
                $allDivisionIds = Division::pluck('id')->toArray(); 
                $process->divisions()->syncWithoutDetaching($allDivisionIds);
            } else if (!empty($validated['division_ids'])) {
                // Для звичайних процесів — тільки ті підрозділи, які обрав користувач
                $process->divisions()->syncWithoutDetaching($validated['division_ids']);
            }

            
            if ($process->wasRecentlyCreated) {
                $message = 'Нову функцію успішно створено, підрозділи закріплено. ';
            } else {
                $message = 'Знайдено існуючу функцію, оновлено її підрозділи та зв\'язки. ';
            }
        }

        // 4. Цикл для збереження зв'язків на рівні самого КНДК (для сумісності зі старою логікою)
        foreach ($validated['kndk_ids'] as $kndkId) {
            $kndk = Kndk::find($kndkId);

            if ($kndk) {
                if (!empty($validated['division_ids'])) {
                    $kndk->divisions()->syncWithoutDetaching($validated['division_ids']);
                }

                if (!empty($validated['position_own_ids'])) {
                    $kndk->responsibles()->syncWithoutDetaching($validated['position_own_ids']);
                }

                if (!empty($validated['position_ids'])) {
                    $kndk->positions()->syncWithoutDetaching($validated['position_ids']);
                }
            }
        }

        // 5. Формування фінального тексту сповіщення та редирект
        if (empty($validated['name'])) {
            return redirect()
                ->back()
                ->withInput()
                ->with('success', 'Зв\'язки підрозділів та посад з обраними КНДК успішно оновлено!');
        } 
        
        

            //  Отримуємо ключові слова з форми
            if ($request->filled('keywords')) {
                $rawKeywords = explode(',', $request->input('keywords'));

                // 2. Очищаємо пробіли та дублікати
                $keywords = collect($rawKeywords)
                    ->map(fn($w) => trim(mb_strtolower($w)))
                    ->filter()
                    ->unique();

                foreach ($keywords as $word) {
                    // 3. Знаходимо або створюємо ключове слово
                    $keyword = Keyword::firstOrCreate(['name' => $word]);

                    // 4. Прив’язуємо до процесу
                    if (!empty($process)) {
                        $process->keywords()->syncWithoutDetaching([$keyword->id]);
                    }

                    // 5. Прив’язуємо до документа (якщо вибраний)
                    if ($request->filled('document_id')) {
                        $document = Document::find($request->input('document_id'));
                        if ($document) {
                            $document->keywords()->syncWithoutDetaching([$keyword->id]);
                        }
                    }

                    // 6. Прив’язуємо до кожного КНДК
                    foreach ($validated['kndk_ids'] as $kndkId) {
                        $kndk = Kndk::find($kndkId);
                        if ($kndk) {
                            $kndk->keywords()->syncWithoutDetaching([$keyword->id]);
                        }
                    }
                }
            }

        // Якщо процес успішно створено/знайдено, краще очистити форму, крім КНДК (опціонально),
        // але якщо ви повертаєте з ->withInput(), форма лишиться заповненою.
        return redirect()
            ->back()
            ->withInput()
            ->with('success', $message . 'Підрозділи та посади також прив\'язані до КНДК.');
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
    public function show($id)
    {
        // 1. Завантажуємо КНДК разом із усіма зв'язками (включаючи підрозділи кожної функції/процесу)
        $item = Kndk::with([
            'documents', 
            'processes.divisions', // ТЕПЕР ЦЕ ПРАЦЮЄ, бо ми створили цей зв'язок у моделі Process
            'divisions', 
            'responsibles', 
            'positions'
        ])->findOrFail($id);
        // 2. Отримуємо унікальні організації з документів
        $organizations = $item->documents
            ->pluck('organization')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        // 3. Шукаємо ВСІ коди форматів X.X або X.X.X у тексті назви КНДК
        preg_match_all('/(?<!\d)\d+\.\d+(?:\.\d+)?(?!\d)/', $item->name, $matches);
        // ВИПРАВЛЕНО: беремо саме індекс, де лежать знайдені рядки
        $foundCodes = isset($matches[0]) ? array_unique($matches[0]) : [];

        // 4. Шукаємо інші пов'язані елементи КНДК за знайденими кодами
        $linkedItems = [];
        if (!empty($foundCodes)) {
            $linkedItems = Kndk::whereIn('full_code', $foundCodes)
                ->where('id', '!=', $item->id)
                ->get()
                ->keyBy('full_code');
        }

        // 5. Розбиваємо оригінальний текст на заголовок (перший рядок) та опис (решта ліній)
        $lines = Str::of($item->name)->explode("\n")->map(fn($line) => trim($line))->filter();
        $title = $lines->first() ?? 'Назва елемента';
        $rawDescription = $lines->skip(1)->implode("\n");

        // 6. Безпечно екрануємо текст та перетворюємо знайдені коди на посилання-бейджи
        $safeDescription = e($rawDescription);
        foreach ($linkedItems as $code => $linkedItem) {
            $route = route('kndks.show', $linkedItem->id);
            $badgeHtml = '<a href="' . $route . '" class="badge bg-primary-subtle text-primary text-decoration-none border border-primary-subtle px-2 py-1 mx-1 fw-bold transition-all">' . e($code) . '</a>';
            
            $safeDescription = str_replace($code, $badgeHtml, $safeDescription);
        }

        // Передаємо всі готові змінні у View
        return view('kndks.show', compact('item', 'title', 'safeDescription', 'linkedItems', 'organizations'));
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

    public function import()
    {
         $kndks = Kndk::orderBy('class', 'asc')->get();
        return view('kndks.import', compact('kndks'));
    }
    public function showImportPage($id)
    {
        // Знаходимо конкретний КНДК або видаємо 404, якщо не знайдено
        $kndk = Kndk::findOrFail($id);

        return view('kndks.import', compact('kndk'));
    }
    // importData
    public function importData(Request $request)
    {
       $file = $request->file('import_file');

        $path = $file->getRealPath();

        // читаємо як Windows-1251
        $content = file_get_contents($path);

        // конвертуємо в UTF-8
        $content = mb_convert_encoding($content, 'UTF-8', 'Windows-1251');

        // тимчасовий файл
        $tempPath = storage_path('app/temp_import.csv');
        file_put_contents($tempPath, $content);

        $handle = fopen($tempPath, 'r');

            $importedCount = 0;

            DB::beginTransaction();

            try {
                while (($row = fgetcsv($handle, 0, ';')) !== false) {

                    if (count(array_filter($row)) < 2) {
                        continue;
                    }

                    $code = trim($row[0]);
                    $name = trim($row[1] ?? '');
                    $positionsRaw = trim($row[2] ?? '');
                    $divisionsRaw = trim($row[3] ?? '');

                    // -------------------------
                    // 1. CODE PARSE
                    // -------------------------
                    $parts = explode('.', $code);

                    $class = $parts[0] ?? null;
                    $subclass = $parts[1] ?? null;
                    $group = $parts[2] ?? null;

                    // -------------------------
                    // 2. CLEAN NAME
                    // -------------------------
                    $name = preg_replace('/[()]/', '', $name);
                    $name = trim(preg_replace('/\s+/', ' ', $name));

                    // -------------------------
                    // 3. KNDK SAVE
                    // -------------------------
                    $kndk = Kndk::updateOrCreate(
                        ['full_code' => $code],
                        [
                            'class' =>  $class,
                            'subclass' => $subclass,
                            'group' => $group,
                            'full_code' => $code,
                            'name' => $name,
                        ]
                    );

                    // -------------------------
                    // 4. POSITIONS (by ABBR)
                    // -------------------------
                    if ($positionsRaw) {
                        $positionCodes = array_map('trim', explode(',', $positionsRaw));

                        $positionIds = Position::whereIn('abv', $positionCodes)
                            ->pluck('id')
                            ->toArray();

                        $kndk->positions()->syncWithoutDetaching($positionIds);
                    }

                    // -------------------------
                    // 5. DIVISIONS
                    // -------------------------
                    if ($divisionsRaw) {
                        $divisionNames = array_map('trim', explode(',', $divisionsRaw));

                        $divisionIds = Division::whereIn('abv', $divisionNames)
                            ->orWhereIn('name', $divisionNames)
                            ->pluck('id')
                            ->toArray();

                        $kndk->divisions()->syncWithoutDetaching($divisionIds);
                    }

                    $importedCount++;
                }

                fclose($handle);
                DB::commit();

                return redirect()->back()->with(
                    'success',
                    "Імпорт завершено! Оброблено: {$importedCount}"
                );

            } catch (\Exception $e) {
                DB::rollBack();
                fclose($handle);
            }

        return redirect()->back()->with('error', $e->getMessage());
     }
    
    public function importCsvPos(Request $request, $id)
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
            $nextKndk = Kndk::where('id', '>', $kndk->id)
                ->orderBy('id', 'asc')
                ->first();

            // 3. Визначаємо ID для перенаправлення (наступний або залишаємо поточний, якщо наступного немає)
            $redirectId = $nextKndk ? $nextKndk->id : $kndk->id;

            // 4. Формуємо повідомлення залежно від того, чи є наступний запис
            $message = "Дані успішно імпортовано! Оброблено документів: {$attachedCount}.";
            if (!$nextKndk) {
                $message .= " Це був останній запис.";
            }

            // 5. Перенаправляємо
            return redirect()
                ->route('kndks.importPage', $redirectId)
                ->with('success', $message);
           

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

        // 2. Вибірка з бази з урахуванням нової моделі Keyword
        $kndks = Kndk::with(['documents.keywords', 'responsibles', 'positions', 'divisions', 'keywords'])
            ->where(function($mainQuery) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $mainQuery->orWhere('full_code', 'LIKE', "%{$term}%")
                        ->orWhere('name', 'LIKE', "%{$term}%")
                        ->orWhereHas('keywords', function($q) use ($term) {
                            $q->where('name', 'LIKE', "%{$term}%"); // Пошук у тегах КНДК
                        })
                        ->orWhereHas('documents', function($q) use ($term) {
                            $q->where('short_content', 'LIKE', "%{$term}%")
                            ->orWhere('organization', 'LIKE', "%{$term}%")
                            ->orWhereHas('keywords', function($sq) use ($term) {
                                $sq->where('name', 'LIKE', "%{$term}%"); // Пошук у тегах документів
                            });
                        })
                        ->orWhereHas('responsibles', function($q) use ($term) { $q->where('name', 'LIKE', "%{$term}%")->orWhere('abv', 'LIKE', "%{$term}%"); })
                        ->orWhereHas('positions', function($q) use ($term) { $q->where('name', 'LIKE', "%{$term}%")->orWhere('abv', 'LIKE', "%{$term}%"); })
                        ->orWhereHas('divisions', function($q) use ($term) { $q->where('name', 'LIKE', "%{$term}%")->orWhere('abv', 'LIKE', "%{$term}%"); });
                }
            })
            ->get();

        $sortedResults = [];

        // 3. Розрахунок РЕЛЕВАНТНОСТІ
        foreach ($kndks as $kndk) {
            $score = 0;

            $kndkMainText = mb_strtolower($kndk->full_code . ' ' . $kndk->name, 'UTF-8');
            
            // Збираємо масив усіх чистих тегів для перевірки пріоритету
            $kndkKeywords = [];
            $keywordsText = '';
            
            foreach ($kndk->keywords as $keyword) {
                $keywordNameLower = mb_strtolower($keyword->name, 'UTF-8');
                $kndkKeywords[] = $keywordNameLower;
                $keywordsText .= ' ' . $keywordNameLower;
            }

            $documentsText = '';
            foreach ($kndk->documents as $doc) {
                $documentsText .= ' ' . mb_strtolower($doc->short_content . ' ' . $doc->organization, 'UTF-8');
                foreach ($doc->keywords as $docKeyword) {
                    $docKeywordNameLower = mb_strtolower($docKeyword->name, 'UTF-8');
                    $kndkKeywords[] = $docKeywordNameLower;
                    $keywordsText .= ' ' . $docKeywordNameLower;
                }
            }

            // Залишаємо лише унікальні теги для оптимізації циклу
            $kndkKeywords = array_unique($kndkKeywords);

            $otherRelationsText = '';
            foreach ([$kndk->responsibles, $kndk->positions, $kndk->divisions] as $relationGroup) {
                foreach ($relationGroup as $itemRow) {
                    $otherRelationsText .= ' ' . mb_strtolower($itemRow->name . ' ' . $itemRow->abv, 'UTF-8');
                }
            }

            // --- ПЕРЕВІРКА 1: Точні збіги фрази ---
            if (mb_strpos($kndkMainText, 'радіаційн', 0, 'UTF-8') !== false && mb_strpos($kndkMainText, 'безпек', 0, 'UTF-8') !== false) {
                $score += 150; 
            }
            if (mb_strpos($kndkMainText, 'аварі', 0, 'UTF-8') !== false) {
                $score += 100;
            }

            // --- ПЕРЕВІРКА 2: Пословний аналіз з динамічною вагою термінів ---
            foreach ($searchTerms as $term) {
                // Динамічний пріоритет: якщо шукане слово присутнє в системних ключових словах (тегах) цієї сутності
                $isHighPriority = false;
                foreach ($kndkKeywords as $keyword) {
                    if (mb_strpos($keyword, $term, 0, 'UTF-8') !== false) {
                        $isHighPriority = true;
                        break;
                    }
                }
                
                // Якщо це офіційно виділене ключове слово — множник х15, інакше х1
                $wordWeight = $isHighPriority ? 15 : 1;

                // Збіг у назві або коді КНДК (вага 20)
                if (mb_strpos($kndkMainText, $term, 0, 'UTF-8') !== false) {
                    $score += (20 * $wordWeight);
                }
                // Збіг безпосередньо у полі ключових слів (вага 12)
                if (mb_strpos($keywordsText, $term, 0, 'UTF-8') !== false) {
                    $score += (12 * $wordWeight);
                }
                // Збіг у тексті документів (вага 5)
                if (mb_strpos($documentsText, $term, 0, 'UTF-8') !== false) {
                    $score += (5 * $wordWeight);
                }
                // Збіг у назвах підрозділів/посад (вага 2)
                if (mb_strpos($otherRelationsText, $term, 0, 'UTF-8') !== false) {
                    $score += (2 * $wordWeight);
                }
            }

            if ($score > 0) {
                $sortedResults[] = [
                    'id' => $kndk->id,
                    'full_code' => $kndk->full_code,
                    'name' => $kndk->name ?? 'Процес КНДК',
                    'score' => $score 
                ];
            }
        }

        usort($sortedResults, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $finalResults = array_slice($sortedResults, 0, 20);

        return response()->json($finalResults);
    }

   

public function searchSimilarPr(Request $request)
{
    // Отримуємо текст з textarea опису
    $searchTerm = $request->input('d', '');
    
    // Очищаємо запит та розбиваємо його на окремі слова (ігноруємо подвійні пробіли)
    $words = array_filter(explode(' ', trim($searchTerm)));

    // Починаємо побудову запиту через модель Process та підвантажуємо підрозділи
    $query = Process::with('divisions','documents','kndks.positions','keywords');

    if (empty($words)) {
        return response()->json([
            'success' => true,
            'data' => $query->limit(5)->get()
        ]);
    }

    // 1. Фільтрація: кожне слово має бути або в імені, або в описі
    $query->where(function ($q) use ($words) {
        foreach ($words as $word) {
            $q->where(function ($subQ) use ($word) {
                $subQ->where('name', 'LIKE', "%{$word}%")
                    ->orWhere('description', 'LIKE', "%{$word}%");
            });
        }
    });

    // 2. Сортування за релевантністю (динамічно будуємо SQL для підрахунку ваги)
    $scoreRaw = "(";
    $bindings = [];

    foreach ($words as $word) {
        // Додаємо бали, якщо слово знайдено в імені (вага 10) або в описі (вага 2)
        // Також додаємо бонус, якщо слово стоїть ближче до початку поля name
        $scoreRaw .= "
            (CASE WHEN LOCATE(?, name) > 0 THEN (10 + (100 / LOCATE(?, name))) ELSE 0 END) +
            (CASE WHEN LOCATE(?, description) > 0 THEN 2 ELSE 0 END) + ";
        
        // Наповнюємо масив біндінгів для безпеки (захист від SQL-інєкцій)
        $bindings[] = $word;
        $bindings[] = $word;
        $bindings[] = $word;
    }
    
    // Закриваємо дужку математичного виразу та прибираємо зайвий плюс наприкінці
    $scoreRaw = rtrim($scoreRaw, ' + ') . ") DESC";

    // Отримуємо 5 відсортованих за релевантністю записів
    $processes = $query
        ->orderByRaw($scoreRaw, $bindings)
        ->orderByRaw("LENGTH(name) ASC") // Коротші назви при однаковій кількості слів йдуть вище
        ->limit(5)
        ->get();

    // Повертаємо стандартизовану JSON-відповідь для AJAX-скрипту
    return response()->json([
        'success' => true,
        'data' => $processes
    ]);
}




}
