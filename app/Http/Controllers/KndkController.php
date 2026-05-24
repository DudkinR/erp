<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kndk;
use App\Models\Document;
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
    public function create()
    {
        //
        return view('kndks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'class'       => 'required|integer',
        'subclass'    => 'nullable|string|size:2',
        'group'       => 'nullable|string|size:2',
        'full_code'   => 'required|string',
        'name'        => 'required|string',
        'object_type' => 'nullable|string|in:документ,функція,захід',
    ]);

    // Важливо: перетворюємо порожні рядки форми в справжній null
    if ($request->input('level_toggle') == 1) {
        $validated['subclass'] = null;
        $validated['group'] = null;
    } elseif ($request->input('level_toggle') == 2) {
        $validated['group'] = null;
    }

    Kndk::create($validated);

    return redirect()->route('kndks.index')->with('success', 'Елемент класифікатора успішно створено!');

      
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
    $item = Kndk::with('documents')->findOrFail($id);

    // Ваша поточна логіка обробки заголовка та опису
    $title = Str::of($item->name)->explode("\n")->first();
    $safeDescription = $item->name; // Або ваша логіка
    $linkedItems = []; // Ваша логіка пошуку відповідностей

    return view('kndks.show', compact('item', 'title', 'safeDescription', 'linkedItems'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $kndk = Kndk::find($id);
        return view('kndks.edit', compact('kndk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $validated = $request->validate([
        'class'       => 'required|integer',
        'subclass'    => 'nullable|string|size:2',
        'group'       => 'nullable|string|size:2',
        'full_code'   => 'required|string' ,
        'name'        => 'required|string',
        'object_type' => 'nullable|string|in:документ,функція,захід',
    ], [
        'full_code.unique' => 'Елемент із цифровим кодом :input вже існує в класифікаторі!',
    ]);

    $kndk=Kndk::find($id);

    // Присвоюємо значення властивостям об'єкта моделі
    $kndk->class = $validated['class'];
    $kndk->name = $validated['name'];
    $kndk->full_code = $validated['full_code'];
    $kndk->object_type = $validated['object_type'] ?: null;

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
}
