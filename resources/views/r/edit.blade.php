@extends('layouts.app')
@section('content')
    <div class="container">
        <!-- Заголовок сторінки -->
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h1>{{ __('Create Multiple Risks') }} ({{ __('r') }})</h1>
                <a class="btn btn-outline-secondary" href="{{ route('r.index') }}">{{ __('Back to List') }}</a>
            </div>
        </div>

        <!-- Помилки валідації масиву -->
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Головна форма пакетної відправки -->
        <form action="{{ route('r.update', $risk->id) }}" method="POST">

            @csrf
            @method('PUT')

            <!-- Динамічний контейнер для блоків ризиків -->
            <div id="risksContainer">
                
                <!-- Блок Ризику #1 (Індекс 0, завантажується за замовчуванням) -->
                <div class="card border-0 shadow-sm mb-4 risk-card" data-index="0">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary fw-bold">#1 {{ __('Ризик') }}</h5>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-risk-btn" style="display: none;">❌ Видалити цей блок</button>
                    </div>
                    <div class="card-body p-4">
                        
                        <!-- Назва ризику -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="risks[0][name]" class="form-control" placeholder="Введіть назву ризику"  value="{{ old('risks.0.name', $risk->name) }}" required>
                        </div>

                        <!-- Опис ризику -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('Description') }}</label>
                            <textarea name="risks[0][description]" rows="2" class="form-control" placeholder="Опис загрози та обов'язків">{{ old('risks.0.description', $risk->description) }}</textarea>
                        </div>

                      
                         <!-- Вибір КНДК для поточного ризику з внутрішнім пошуком -->
                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">{{ __('Select KNDK Activities') }} <span class="text-danger">*</span></label>
                            
                            <!-- Поле пошуку -->
                            <input type="text" class="form-control form-control-sm mb-2 kndk-search-filter" placeholder="🔍 Швидкий фільтр КНДК для цього ризику...">
                            
                            <!-- Панель швидкого вибору -->
                            <div class="mb-2 d-flex gap-2">
                                <button type="button" class="btn btn-xs btn-outline-primary btn-sm select-all-kndk-btn" style="font-size: 0.8rem;">🔹 {{ __('Вибрати всі відфільтровані') }}</button>
                                <button type="button" class="btn btn-xs btn-outline-secondary btn-sm deselect-all-kndk-btn" style="font-size: 0.8rem;">⬜ {{ __('Скинути вибір') }}</button>
                            </div>

                            <!-- Простий список-бокс, що імітує мультиселект -->
                            <div class="border rounded p-2 bg-white kndk-multiselect-box" style="max-height: 180px; overflow-y: auto;">
                                @foreach($kndks as $kndk)
                                    <div class="form-check kndk-option-item">
                                        <input class="form-check-input kndk-checkbox" type="checkbox" name="risks[0][kndk_ids][]" value="{{ $kndk->id }}" id="kndk_0_{{ $kndk->id }}"  
                                        @checked(in_array($kndk->id, old('risks.0.kndk_ids', $risk->kndks->pluck('id')->toArray())))
                                        >
                                        <label class="form-check-label text-wrap w-100" for="kndk_0_{{ $kndk->id }}">
                                            {{ $kndk->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>




                    </div>
                </div>

            </div>

            <!-- Нижня панель кнопок керування формою -->
            <div class="row mb-5">
                <div class="col-md-12 d-flex justify-content-between gap-3">
                     <button type="submit" class="btn btn-success btn-lg px-5">{{ __('Save Risk') }}</button>
                </div>
            </div>
        </form>
    </div>

@endsection