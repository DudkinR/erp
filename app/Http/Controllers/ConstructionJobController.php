<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConstructionJob;
use Symfony\Component\HttpFoundation\StreamedResponse;
class ConstructionJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = ConstructionJob::where('year', 2026)->get();

        $months = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];

        // Загальні підсумки
        $totalWhh = $jobs->sum('whh');
        $avgWhh = $jobs->avg('whh');
        $invalidWhhCount = $jobs->filter(function($job) use ($months) {
            $sumMonths = collect($months)->sum(fn($m) => $job->$m ?? 0);
            return abs($job->whh - $sumMonths) > 0.0001;
        })->count();

        // Агрегація по місяцях
        $stats = [];
        foreach ($months as $m) {
            $stats[$m] = $jobs->sum($m);
        }

        // Агрегація по цехах
        $divisionStats = $jobs->groupBy('own_division')->map(function($group) use ($months) {
            $sum = [];
            foreach ($months as $m) {
                $sum[$m] = $group->sum($m);
            }
            return [
                'sumMonths' => $sum,
                'totalWhh' => $group->sum('whh'),
                'avgWhh' => $group->avg('whh'),
            ];
        });

        // Агрегація по об’єктах
        $basisStats = $jobs->groupBy('basis')->map(function($group) use ($months) {
            $sum = [];
            foreach ($months as $m) {
                $sum[$m] = $group->sum($m);
            }
            return [
                'sumMonths' => $sum,
                'totalWhh' => $group->sum('whh'),
                'avgWhh' => $group->avg('whh'),
            ];
        });

        return view('jobs.index', compact(
            'jobs','stats','divisionStats','basisStats',
            'totalWhh','avgWhh','invalidWhhCount'
        ));
    }

       
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('jobs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // Валідація даних
        $validatedData = $request->validate([
            'basis'         => 'required|string|max:255',
            'build'         => 'nullable|string|max:255',
            'room'          => 'nullable|string|max:255',
            'location_axes' => 'nullable|string|max:255',
            'element'       => 'nullable|string|max:255',
            'work_type'     => 'required|string|max:255',
            'unit'          => 'nullable|string|max:50',
            'q'             => 'required|numeric',
            'whh'           => 'required|numeric',
            'type'          => 'required|string|max:255',
            'tmc'           => 'nullable|string',
            'inv_no'        => 'nullable|string|max:255',
            'own_division'  => 'required|string|max:255',
            'note_locale'   => 'nullable|string',
            'note'          => 'nullable|string',
            'grant'         => 'nullable|string|max:255',

            // Місяці
            'jan' => 'nullable|numeric',
            'feb' => 'nullable|numeric',
            'mar' => 'nullable|numeric',
            'apr' => 'nullable|numeric',
            'may' => 'nullable|numeric',
            'jun' => 'nullable|numeric',
            'jul' => 'nullable|numeric',
            'aug' => 'nullable|numeric',
            'sep' => 'nullable|numeric',
            'oct' => 'nullable|numeric',
            'nov' => 'nullable|numeric',
            'dec' => 'nullable|numeric',
        ]);

        // Додаємо рік
        $validatedData['year'] = 2026;

        // Перевірка відповідності WHH сумі місяців
        $months = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
        $sumMonths = 0;
        foreach ($months as $m) {
            $sumMonths += $validatedData[$m] ?? 0;
        }

        if ($validatedData['whh'] != $sumMonths) {
            return back()
                ->withErrors([
                    'whh' => "WHH ({$validatedData['whh']}) не співпадає із сумою місяців ({$sumMonths})."
                ])
                ->withInput();
        }

        // Створення запису
        ConstructionJob::create($validatedData);

        return redirect()
            ->route('constructionjobs.index')
            ->with('success', 'Робота успішно створена.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = ConstructionJob::findOrFail($id);
        return view('jobs.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $job = ConstructionJob::findOrFail($id);
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $job = ConstructionJob::findOrFail($id);
       // return $request;
        // Валідація даних
        $validatedData = $request->validate([
            'basis'         => 'required|string|max:255',
            'build'         => 'nullable|string|max:255',
            'room'          => 'nullable|string|max:255',
            'location_axes' => 'nullable|string|max:255',
            'element'       => 'nullable|string|max:255',       
            'work_type'     => 'required|string|max:255',
            'unit'          => 'nullable|string|max:50',
            'q'             => 'required|numeric',
            'whh'           => 'required|numeric',
            'type'          => 'required|string|max:255',
            'tmc'           => 'nullable|string',
            'inv_no'        => 'nullable|string|max:255',
            'own_division' => 'nullable|string|max:255',
            'note_locale'   => 'nullable|string',
            'note'          => 'nullable|string',
            'grant'         => 'nullable|string|max:255',
            // Місяці
            'jan' => 'nullable|numeric',
            'feb' => 'nullable|numeric',
            'mar' => 'nullable|numeric',
            'apr' => 'nullable|numeric',
            'may' => 'nullable|numeric',
            'jun' => 'nullable|numeric',
            'jul' => 'nullable|numeric',
            'aug' => 'nullable|numeric',
            'sep' => 'nullable|numeric',
            'oct' => 'nullable|numeric',
            'nov' => 'nullable|numeric',
            'dec' => 'nullable|numeric',
        ]);
        // Перевірка відповідності WHH сумі місяців
        $months = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
        $sumMonths = 0;
        foreach ($months as $m) {
            $sumMonths += $validatedData[$m] ?? 0;
        }
        $validatedData['year'] = 2026; // Додаємо рік
        $job->update($validatedData);
        return redirect()
            ->route('constructionjobs.index')
            ->with('success', 'Робота успішно оновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $job = ConstructionJob::findOrFail($id);
        $job->delete();
        return redirect()
            ->route('constructionjobs.index')
            ->with('success', 'Робота успішно видалена.');  
    }
    // addmonth
    public function addmonth(Request $request, string $id)
    {
        $job = ConstructionJob::findOrFail($id);

        // Валідація даних
        $validatedData = $request->validate([
            'month' => 'required|in:jan,feb,mar,apr,may,jun,jul,aug,sep,oct,nov,dec',
            'real_value' => 'required|numeric',
        ]);

        // Формуємо назву фактичного поля (real_jan, real_feb ...)
        $realField = 'real_' . $validatedData['month'];

        // Оновлюємо значення
        $job->{$realField} = $validatedData['real_value'];
        
        // Перерахунок суми фактичних місяців
        $months = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
        $sumRealMonths = 0;
        foreach ($months as $m) {
            $sumRealMonths += $job->{'real_'.$m} ?? 0;
        }
        //return $sumRealMonths;
        // Перевірка відповідності real_whh сумі фактичних місяців
        $comment = "Фактичний WHH ({$job->whh}) / сумою фактичних місяців ({$sumRealMonths}).";
        
        $job->save();

        return redirect()
            ->route('constructionjobs.show', ['id' => $job->id])
            ->with('success', $comment);
    }


    // show the form for uploading CSV and processing it
    public function index_csv() {
        return view('jobs.import');
    }

    // обробки та записи моделі
    public function download(Request $request) {
        // 1. Валідація вхідного файлу
        $request->validate([
        'file' => 'required|file|mimes:csv,txt',
        'type_of_file' => 'required|in:windows-1251,utf-8',
        ]);

        $year = 2026;            
        $file = $request->file('file');
        $encoding = $request->input('type_of_file');
         $separator = ';';
    // ... код визначення $separator, $year, $encoding тощо ...
    function parseNumber($value) {
        // 1. Якщо значення null або порожнє, повертаємо 0
        if ($value === null || trim($value) === '') {
            return 0;
        }

        // 2. Видаляємо всі нерозривні та звичайні пробіли
        $clean = str_replace([' ', "\x0c", "\x0b", "\x0a", "\x0d", "\t", "\xc2\xa0"], '', $value);

        // 3. Замінюємо кому на крапку для коректного десяткового формату
        $clean = str_replace(',', '.', $clean);

        // 4. Залишаємо лише цифри, мінус та крапку
        $clean = preg_replace('/[^\d.-]/', '', $clean);

        // 5. Перевіряємо, чи це дробове чи ціле число
        return (strpos($clean, '.') !== false) ? (float)$clean : (int)$clean;
    }
    // 1. Спочатку лікуємо кодування всього файлу, щоб не було ієрогліфів
    $fileContent = file_get_contents($file->getRealPath());

    if (str_starts_with($fileContent, "\xff\xfe") || str_starts_with($fileContent, "\xfe\xff") || strpos($fileContent, "\x00") !== false) {
        $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'Windows-1251');
    } elseif ($encoding === 'windows-1251') {
        $fileContent = mb_convert_encoding($fileContent, 'UTF-8', 'Windows-1251');
    }

    // 2. Відкриваємо чистий потоковий буфер у пам'яті для економії RAM (php://temp)
    $handle = fopen('php://temp', 'r+');
    fwrite($handle, $fileContent);
    rewind($handle);

    $currentDivision = null;
    $rowCount = 0;

    // 3. Построковий парсинг через звичайний цикл (БЕЗ StreamedResponse)
    while (($row = fgetcsv($handle, 0, $separator)) !== false) {
    // return $row;
        $rowCount++;

        // Очищаємо елементи рядка
        $row = array_map(function($value) {
            return trim(preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $value));
        }, $row);

        // Пропускаємо порожні рядки
        if (empty(array_filter($row, function($val) { return $val !== ''; }))) {
            continue;
        }

        // Пропускаємо технічний заголовок таблиці
        if (isset($row[1]) && (mb_strtolower($row[1]) === 'назва будівлі' || strtolower($row[1]) === 'build')) {
            continue;
        }

        // --- ЛОГІКА ВИЗНАЧЕННЯ ПІДРОЗДІЛУ (DIVISION) ---
        $firstElement = $row[0] ?? '';
        $otherElements = array_slice($row, 1);
        
        $isOthersEmpty = true;
        foreach ($otherElements as $element) {
            if ($element !== '') {
                $isOthersEmpty = false;
                break;
            }
        }

        if ($firstElement !== '' && $isOthersEmpty) {
            $currentDivision = $firstElement;
            continue; 
        }

        if (!$currentDivision) {
            continue;
        }

        // Формуємо масив даних для збереження
        $dataToSave = [
            'basis'         => $currentDivision, 
            'build'         => $row[1] ?? null,
            'room'          => $row[2] ?? null,
            'location_axes' => $row[3] ?? null,
            'element'       => $row[4] ?? null,
            'work_type'     => $row[5] ?? null,
            'unit'          => $row[6] ?? null,
            'q'             => parseNumber($row[7] ?? null),
            'whh'           => parseNumber($row[8] ?? null),
            'type'          => $row[9] ?? null,
            'year'          => $year,
            'jan'           => parseNumber($row[10] ?? null),
            'feb'           => parseNumber($row[11] ?? null),
            'mar'           => parseNumber($row[12] ?? null),
            'apr'           => parseNumber($row[13] ?? null),
            'may'           => parseNumber($row[14] ?? null),
            'jun'           => parseNumber($row[15] ?? null),
            'jul'           => parseNumber($row[16] ?? null),
            'aug'           => parseNumber($row[17] ?? null),
            'sep'           => parseNumber($row[18] ?? null),
            'oct'           => parseNumber($row[19] ?? null),
            'nov'           => parseNumber($row[20] ?? null),
            'dec'           => parseNumber($row[21] ?? null),
            'tmc'           => $row[22] ?? null,
            'inv_no'        => $row[23] ?? null,
            'own_division'  => $row[24] ?? null,
            'note_locale'   => $row[25] ?? null,
            'note'          => $row[26] ?? null,
            'grant'         => $row[27] ?? null,
        ];

        // ДЕБАГ: Зупиняємо код тут, щоб перевірити першу чисту кириличну структуру
        //  dd($dataToSave);

        // 4. Збереження моделі в базу даних MySQL
        try {
            ConstructionJob::create($dataToSave);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Помилка імпорту рядка {$rowCount}: " . $e->getMessage());
        }
    }

    fclose($handle);

    return response()->json([
        'success' => true,
        'message' => "Імпорт завершено. Оброблено рядків: {$rowCount}"
    ]);

    }
    public function construction_jobs(Request $request) {
    // 1. Отримуємо місяць з запиту (наприклад, 'jul'). Якщо порожньо — поточний місяць.
        $month = $request->input('m', strtolower(date('M')));

        // 2. Вибираємо ВСІ поля, де значення поточного місяця НЕ дорівнює 0 і НЕ є null
        $jobs = ConstructionJob::where($month, '!=', 0)
            ->whereNotNull($month)
            ->get();

        // 3. Формуємо масив даних для CSV
        $csvData = [];
        
        // Заголовки для всіх полей таблиці
        $csvData[] = [
            'ID', 'Basis', 'Build', 'Room', 'Location Axes', 'Element', 'Work Type', 'Unit', 
            'Q', 'WHH', 'Type', 'Year', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'TMC', 'Inv No', 'Own Division', 
            'Note Locale', 'Note', 'Grant', 'Created At', 'Updated At'
        ];

        foreach ($jobs as $job) {
            $csvData[] = [
                $job->id,
                $job->basis,
                $job->build,
                $job->room,
                $job->location_axes,
                $job->element,
                $job->work_type,
                $job->unit,
                $this->parseNumber($job->q),
                $this->parseNumber($job->whh),
                $job->type,
                $job->year,
                $this->parseNumber($job->jan),
                $this->parseNumber($job->feb),
                $this->parseNumber($job->mar),
                $this->parseNumber($job->apr),
                $this->parseNumber($job->may),
                $this->parseNumber($job->jun),
                $this->parseNumber($job->jul),
                $this->parseNumber($job->aug),
                $this->parseNumber($job->sep),
                $this->parseNumber($job->oct),
                $this->parseNumber($job->nov),
                $this->parseNumber($job->dec),
                $job->tmc,
                $job->inv_no,
                $job->own_division,
                $job->note_locale,
                $job->note,
                $job->grant,
                $job->created_at,
                $job->updated_at,
            ];
        }

        // 4. Створення StreamedResponse для скачування файлу
        $response = new StreamedResponse(function () use ($csvData) {
            $handle = fopen('php://output', 'w');
            
            // Додаємо UTF-8 BOM для відображення кирилиці в Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            
            foreach ($csvData as $row) {
                fputcsv($handle, $row, ';'); // Роздільник крапка з комою
            }
            fclose($handle);
        });

        // 5. Налаштування HTTP-заголовків скачування
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="filtered_jobs_' . $month . '_' . time() . '.csv"');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }

    /**
     * Функція безпечного парсингу чисел
     */
    private function parseNumber($value)
    {
        if ($value === null || trim($value) === '') {
            return 0;
        }
        $clean = str_replace([' ', "\x0c", "\x0b", "\x0a", "\x0d", "\t", "\xc2\xa0"], '', $value);
        $clean = str_replace(',', '.', $clean);
        $clean = preg_replace('/[^\d.-]/', '', $clean);

        return (strpos($clean, '.') !== false) ? (float)$clean : (int)$clean;
    }

}
