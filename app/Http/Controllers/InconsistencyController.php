<?php

namespace App\Http\Controllers;
use App\Models\Inconsistency;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InconsistencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        // Зауваження користувача (тільки його власні)
        $userInconsistencies = Inconsistency::whereHas('users', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('role', 'creator');
        })->with(['documents', 'authorResponses'])->orderByDesc('id') ->get();

        // Для служби якості: ті, що ще не оброблені або потребують перевірки
        $qaInconsistencies = [];
        if ($user->hasRole('quality-engineer')) {
            $qaInconsistencies = Inconsistency::whereIn('status', ['created', 'author_fixing'])
                ->with(['documents', 'users', 'authorResponses'])->orderByDesc('id') 
                ->get();
        }

        // Для автора: тільки ті, що стосуються його документів
        $authorInconsistencies = [];
        if ($user->hasRole('author')) {
            $authorInconsistencies = Inconsistency::whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('role', 'author');
            })->with(['documents', 'authorResponses'])->orderByDesc('id') ->get();
        }

        return view('inconsistencis.index', compact(
            'userInconsistencies',
            'qaInconsistencies',
            'authorInconsistencies'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Отримуємо всі документи для вибору
        $documents = \App\Models\Document::all();

        // Можна також передати користувача, якщо треба
        $user = auth()->user();

        return view('inconsistencis.create', compact('documents', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     */




public function store(Request $request)
{
    // 1. Валідація нових масивних даних
    $validated = $request->validate([
        'document_inv_no'   => 'required|array|min:1',
        'document_inv_no.*' => 'required|string|exists:documents,inv_no',
        'points'            => 'required|array|min:1',
        'points.*.point'    => 'required|string|max:255',
        'points.*.current_text'  => 'nullable|string',
        'points.*.proposed_text' => 'nullable|string',
        'points.*.reason'        => 'nullable|string',
    ]);

    // Тимчасовий масив для збереження ID створених записів, якщо знадобиться далі
    $createdInconsistenciesCount = 0;

    // 2. Безпечне збереження через транзакцію бази даних
    DB::transaction(function () use ($validated, &$createdInconsistenciesCount) {
        
        // Перебираємо кожен документ із масиву
        foreach ($validated['document_inv_no'] as $invNo) {
            
            // Перебираємо кожен пункт із форми
            foreach ($validated['points'] as $pointData) {
                
                // Створюємо нову невідповідність для поточної пари Документ + Пункт
                $inconsistency = Inconsistency::create([
                    'point'         => $pointData['point'],
                    'current_text'  => $pointData['current_text'] ?? null,
                    'proposed_text' => $pointData['proposed_text'] ?? null,
                    'reason'        => $pointData['reason'] ?? null,
                    'status'        => 'created',
                ]);

                // Прив’язка поточної невідповідності до конкретного інвентарного номера
                $inconsistency->documents()->attach($invNo);

                // Прив’язка поточного користувача як "creator" до цього запису
                $inconsistency->users()->attach(auth()->id(), ['role' => 'creator']);

                $createdInconsistenciesCount++;
            }
        }
    });

    // 3. Формуємо повідомлення про успіх (показуємо скільки всього невідповідностей згенеровано)
    $message = "Успішно створено зауважень: {$createdInconsistenciesCount}.";

    // Для редіректу беремо перший інвентарний номер з масиву, щоб автопошук на сторінці відпрацював коректно
    $firstInvNo = $validated['document_inv_no'][0];

    return redirect()
        ->route('inconsistencis.create')
        ->with('success', $message)
        ->with('document_inv_no', $firstInvNo);
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

   public function searchDocuments(Request $request)
{
    $query = $request->input('q');

    // Очищаємо запит від зайвих пробілів і розбиваємо на окремі слова
    $words = array_filter(explode(' ', $query), function($word) {
        return mb_strlen(trim($word)) >= 2;
    });

    if (!empty($words)) {
        $selectScores = [];
        $bindings = [];

        // Формуємо динамічний підрахунок балів за допомогою знаків "?"
        foreach ($words as $word) {
            $selectScores[] = "(CASE WHEN inv_no LIKE ? THEN 1 ELSE 0 END +
                                CASE WHEN code LIKE ? THEN 1 ELSE 0 END +
                                CASE WHEN organization LIKE ? THEN 1 ELSE 0 END +
                                CASE WHEN short_content LIKE ? THEN 1 ELSE 0 END)";
            
            // Додаємо значення у строгому порядку для кожного "?"
            $searchTerm = "%{$word}%";
            $bindings[] = $searchTerm; // для inv_no
            $bindings[] = $searchTerm; // для code
            $bindings[] = $searchTerm; // для organization
            $bindings[] = $searchTerm; // для short_content
        }

        // Об'єднуємо всі блоки через плюс
        $scoreRaw = implode(' + ', $selectScores) . ' AS relevance_score';

        $documents = Document::query()
            ->select('*')
            ->selectRaw($scoreRaw, $bindings) // Передаємо плоский масив біндінгів
            ->where(function ($q) use ($words) {
                foreach ($words as $word) {
                    $q->orWhere('inv_no', 'like', "%{$word}%")
                      ->orWhere('code', 'like', "%{$word}%")
                      ->orWhere('organization', 'like', "%{$word}%")
                      ->orWhere('short_content', 'like', "%{$word}%");
                }
            })
            ->orderByDesc('relevance_score') // Спочатку ті, де більше співпадінь
            ->limit(10)
            ->get();
    } else {
        $documents = collect();
    }

    return response()->json($documents);
}

    public function approve(Request $request, $id)
    {
        $inconsistency = Inconsistency::findOrFail($id);
        $inconsistency->proposed_text = $request->input('proposed_text'); // зберігаємо лише при погодженні
        $inconsistency->status = 'approved';
        $inconsistency->qa_confirmation = true;
        $inconsistency->save();

        return response()->json(['success' => true]);
    }

    public function reject($id)
    {
        $inconsistency = Inconsistency::findOrFail($id);
        $inconsistency->status = 'rejected';
        $inconsistency->qa_confirmation = false;
        $inconsistency->save();

        return response()->json(['success' => true]);
    }

}
