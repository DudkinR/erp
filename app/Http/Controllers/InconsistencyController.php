<?php

namespace App\Http\Controllers;
use App\Models\Inconsistency;
use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;

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
        })->with(['documents', 'authorResponses'])->get();

        // Для служби якості: ті, що ще не оброблені або потребують перевірки
        $qaInconsistencies = [];
        if ($user->hasRole('quality-engineer')) {
            $qaInconsistencies = Inconsistency::whereIn('status', ['created', 'author_fixing'])
                ->with(['documents', 'users', 'authorResponses'])
                ->get();
        }

        // Для автора: тільки ті, що стосуються його документів
        $authorInconsistencies = [];
        if ($user->hasRole('author')) {
            $authorInconsistencies = Inconsistency::whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('role', 'author');
            })->with(['documents', 'authorResponses'])->get();
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
        // Валідація даних
        $validated = $request->validate([
            'document_inv_no' => 'required|string|exists:documents,inv_no',
            'point'           => 'required|string|max:255',
            'current_text'    => 'nullable|string',
            'proposed_text'   => 'nullable|string',
            'reason'          => 'nullable|string',
        ]);

        // Створюємо нову невідповідність
        $inconsistency = Inconsistency::create([
            'point'         => $validated['point'],
            'current_text'  => $validated['current_text'] ?? null,
            'proposed_text' => $validated['proposed_text'] ?? null,
            'reason'        => $validated['reason'] ?? null,
            'status'        => 'created',
        ]);

        // Прив’язка до документа
        $inconsistency->documents()->attach($validated['document_inv_no']);

        // Прив’язка до користувача як "creator"
        $inconsistency->users()->attach(auth()->id(), ['role' => 'creator']);

        return redirect()
            ->route('inconsistencis.index')
            ->with('success', 'Невідповідність успішно створена!');
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

        $documents = Document::query()
            ->where('inv_no', $query) // точний пошук по інвентарному
            ->orWhere('code', $query) // точний пошук по шифру
            ->orWhere('organization', 'like', "%{$query}%")
            ->orWhere('short_content', 'like', "%{$query}%")
            ->limit(10)
            ->get();

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
