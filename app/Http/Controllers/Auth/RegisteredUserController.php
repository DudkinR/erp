<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Personal;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
      //  return view('welcome');
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            // Перевіряємо, чи існує цей TN в таблиці personal і чи він ще не зайнятий в users

        'tn' => ['required', 'numeric', 'unique:users,tn'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        'name' => ['nullable', 'string', 'max:255'],
    ], [
        // Кастомні повідомлення для поля tn
        'tn.exists' => 'Співробітника з таким табельним номером не знайдено в базі кадрів.',
        'tn.unique' => 'Користувач із цим табельним номером вже зареєстрований.',
        'tn.numeric' => 'Табельний номер має містити лише цифри.',
        'tn.required' => 'Будь ласка, вкажіть табельний номер.',
    ]);


        // Знаходимо співробітника за ТН для імпорту його ПІБ
        $personal = Personal::where('tn', $request->tn)->first();

        // Якщо користувач не ввів ім'я вручну, беремо `fio` з картки персоналу
        $name = $request->name ?? $personal->fio ?? 'Користувач ' . $request->tn;

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'tn' => $request->tn,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
