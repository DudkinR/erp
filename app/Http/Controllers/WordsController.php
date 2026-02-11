<?php

namespace App\Http\Controllers;

use App\Models\Word;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WordsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id(); // same as Auth::user()->id
    $words = Word::whereHas('users', function ($q) use ($userId) {
        $q->where('user_id', $userId);
    })->get();

    return view('words.index', compact('words'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('words.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bedword' => 'required|string|max:128',
            'comment' => 'nullable|string|max:128',
            'type'    => 'required|integer|in:1,2,3',
        ]);

        // Try to find an existing word with same bedword/type/comment
        $word = Word::where('bedword', $validated['bedword'])
                    ->where('type', $validated['type'])
                    ->where('comment', $validated['comment'] ?? null)
                    ->first();

        if (!$word) {
            // If not found, create new
            $word = Word::create($validated);
        }

        // Attach current user if not already attached
        if (!$word->users()->where('user_id', Auth::id())->exists()) {
            $word->users()->attach(Auth::id());
        }

        return redirect()->route('words.index')->with('success', __('Word saved successfully.'));
    }



    /**
     * Display the specified resource.
     */
        public function show($tn)
        {
            // Find the user by tn
            $user = User::where('tn', $tn)->firstOrFail();
            $words = Word::whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->get();
 return response()->json($words);


        }


    /**
     * Show the form for editing the specified resource.
     */


    public function edit(Word $word)
    {
        // Only allow editing if the word belongs to the current user
        if (!$word->users()->where('user_id', Auth::id())->exists()) {
            abort(403, 'Unauthorized');
        }

        return view('words.edit', compact('word'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Word $word)
    {
        // Ensure the word belongs to the current user
        if (!$word->users()->where('user_id', Auth::id())->exists()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'bedword' => 'required|string|max:128',
            'comment' => 'nullable|string|max:128',
            'type'    => 'required|integer|in:1,2,3',
        ]);

        // If other users are connected, don't overwrite the shared word
        if ($word->users()->count() > 1) {
            // Detach current user from this shared word
            $word->users()->detach(Auth::id());

            // Try to find an existing word with same new values
            $existing = Word::where('bedword', $validated['bedword'])
                            ->where('type', $validated['type'])
                            ->where('comment', $validated['comment'] ?? null)
                            ->first();

            if (!$existing) {
                // Create a new word if none matches
                $existing = Word::create($validated);
            }

            // Attach current user to the new/existing word
            $existing->users()->attach(Auth::id());
        } else {
            // If only this user is connected, safe to update directly
            $word->update($validated);
        }

        return redirect()->route('words.index')->with('success', __('Word updated successfully.'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Word $word)
    {
        // Ensure current user is attached
        if (!$word->users()->where('user_id', Auth::id())->exists()) {
            abort(403, 'Unauthorized');
        }

        // Detach current user
        $word->users()->detach(Auth::id());

        // If no other users remain connected, delete the word
        if ($word->users()->count() === 0) {
            $word->delete();
        }

        return redirect()->route('words.index')->with('success', __('Word deleted successfully.'));
    }

}
