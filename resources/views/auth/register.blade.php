@extends('layouts.app')
@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        
        <!-- Заголовок картки -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Реєстрація у системі</h2>
            <p class="text-xs text-gray-500 mt-1">Для створення облікового запису використайте свій табельний номер</p>
        </div>

        <!-- Форма -->
        <form action="{{ route('register') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Табельний номер (tn) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="tn">
                    Табельний номер (ТН) <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="tn" 
                    name="tn" 
                    value="{{ old('tn') }}" 
                    placeholder="Наприклад: 1452" 
                    required 
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('tn') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                >
                @error('tn') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="email">
                    Електронна пошта <span class="text-red-500">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="example@company.com" 
                    required 
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                >
                @error('email') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Ім'я (Опціонально) -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="name">
                    Ім'я / ПІБ <span class="text-gray-400 text-xs">(необов'язково)</span>
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    placeholder="Залиште пустим для автоматичного заповнення" 
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                >
                <p class="text-gray-400 text-xxs mt-0.5">Якщо залишити пустим, ПІБ автоматично підтягнеться з бази кадрів.</p>
                @error('name') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Пароль -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password">
                    Пароль <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required 
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 @error('password') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                >
                @error('password') 
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p> 
                @enderror
            </div>

            <!-- Підтвердження паролю -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="password_confirmation">
                    Підтвердження паролю <span class="text-red-500">*</span>
                </label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required 
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
            </div>

            <!-- Кнопка відправки -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md text-sm transition duration-150 ease-in-out shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Зареєструватися
                </button>
            </div>
            
            <!-- Посилання на логін -->
            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="text-xs text-blue-600 hover:underline">
                    Вже маєте обліковий запис? Увійти
                </a>
            </div>
        </form>
    </div>
</div>
@endsection