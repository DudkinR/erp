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
        <form action="{{ route('r.store') }}" method="POST">
            @csrf

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
                            <input type="text" name="risks[0][name]" class="form-control" placeholder="Введіть назву ризику" required>
                        </div>

                        <!-- Опис ризику -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ __('Description') }}</label>
                            <textarea name="risks[0][description]" rows="2" class="form-control" placeholder="Опис загрози та обов'язків"></textarea>
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
                                        <input class="form-check-input kndk-checkbox" type="checkbox" name="risks[0][kndk_ids][]" value="{{ $kndk->id }}" id="kndk_0_{{ $kndk->id }}">
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
                    <button type="button" id="addMoreRiskBtn" class="btn btn-dark px-4">➕ Додати ще один ризик</button>
                    <button type="submit" class="btn btn-success btn-lg px-5">{{ __('Save All Risks') }}</button>
                </div>
            </div>
        </form>
    </div>

    <!-- ТЕМПЛЕЙТ ШАБЛОНУ ДЛЯ КЛОНУВАННЯ ЧЕРЕЗ JAVASCRIPT -->
    <template id="riskTemplate">
        <div class="card border-0 shadow-sm mb-4 risk-card" data-index="__INDEX__">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary fw-bold">#__NUMBER__ {{ __('Ризик') }}</h5>
                <button type="button" class="btn btn-sm btn-outline-danger delete-risk-btn">❌ Видалити цей блок</button>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Name') }} <span class="text-danger">*</span></label>
                    <input type="text" name="risks[__INDEX__][name]" class="form-control" placeholder="Введіть назву ризику" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">{{ __('Description') }}</label>
                    <textarea name="risks[__INDEX__][description]" rows="2" class="form-control" placeholder="Опис загрози та обов'язків"></textarea>
                </div>

                <!-- Вибір КНДК для поточного ризику з внутрішнім пошуком -->
                <div class="mb-3">
                    <label class="form-label fw-bold d-block">{{ __('Select KNDK Activities') }} <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm mb-2 kndk-search-filter" placeholder="🔍 Швидкий фільтр КНДК для цього ризику...">
                    
                    <!-- Панель швидкого вибору -->
                    <div class="mb-2 d-flex gap-2">
                        <button type="button" class="btn btn-xs btn-outline-primary btn-sm select-all-kndk-btn" style="font-size: 0.8rem;">🔹 {{ __('Вибрати всі відфільтровані') }}</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary btn-sm deselect-all-kndk-btn" style="font-size: 0.8rem;">⬜ {{ __('Скинути вибір') }}</button>
                    </div>
                    
                    <!-- Замініть індекс [__INDEX__] на [0] для першого блоку за замовчуванням, а в <template> залиште як є -->
                    <div class="border rounded p-2 bg-white kndk-multiselect-box" style="max-height: 180px; overflow-y: auto;">
                        @foreach($kndks as $kndk)
                            <div class="form-check kndk-option-item">
                                <input class="form-check-input kndk-checkbox" type="checkbox" name="risks[__INDEX__][kndk_ids][]" value="{{ $kndk->id }}" id="kndk___INDEX___{{ $kndk->id }}">
                                <label class="form-check-label text-wrap w-100" for="kndk___INDEX___{{ $kndk->id }}">
                                    {{ $kndk->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>



            </div>
        </div>
    </template>

    <!-- СТИЛІ ОФОРМЛЕННЯ КНДК ТЕГІВ -->
    <style>
        .kndk-checkbox:checked + .kndk-label {
            background-color: #e0f2fe !important;
            border-color: #0284c7 !important;
            color: #0369a1 !important;
            font-weight: 500;
        }
        .kndk-label {
            transition: all 0.15s ease-in-out;
            background-color: #fff;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .kndk-label:hover {
            border-color: #0284c7;
            background-color: #f8fafc;
        }
        .border-dashed {
            border-style: dashed !important;
        }
    </style>

    <!-- СКРИПТ ДИНАМІКИ ТА ІНДЕКСАЦІЇ ФОРМИ -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('risksContainer');
    const addButton = document.getElementById('addMoreRiskBtn');
    const template = document.getElementById('riskTemplate').innerHTML;
    let currentCount = 1;

    // Клікер для кнопки "Додати ще один ризик"
    addButton.addEventListener('click', function () {
        let html = template
            .replace(/__INDEX__/g, currentCount)
            .replace(/__NUMBER__/g, currentCount + 1);

        container.insertAdjacentHTML('beforeend', html);
        currentCount++;
        
        toggleDeleteButtons();
    });

    // Видалення блоку ризику
    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('delete-risk-btn')) {
            const card = e.target.closest('.risk-card');
            card.remove();
            reindexCards();
        }
    });

    // Живий фільтр списку КНДК
    container.addEventListener('input', function (e) {
        if (e.target.classList.contains('kndk-search-filter')) {
            const query = e.target.value.toLowerCase().trim();
            const card = e.target.closest('.risk-card');
            const items = card.querySelectorAll('.kndk-option-item');

            items.forEach(item => {
                const text = item.querySelector('.form-check-label').textContent.toLowerCase();
                if (text.includes(query)) {
                    item.classList.remove('d-none'); 
                } else {
                    item.classList.add('d-none');    
                }
            });
        }
    });

    // Нова логіка: Обробка кнопок "Вибрати всі" та "Скинути вибір"
    container.addEventListener('click', function (e) {
        // Знаходимо картку конкретного ризику, в якій відбувся клік
        const card = e.target.closest('.risk-card');
        if (!card) return;

        // Кнопка "Вибрати всі відфільтровані"
        if (e.target.classList.contains('select-all-kndk-btn')) {
            // Шукаємо лише ті КНДК елементи, які зараз НЕ приховані пошуком (не мають класу d-none)
            const visibleItems = card.querySelectorAll('.kndk-option-item:not(.d-none) .kndk-checkbox');
            visibleItems.forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        // Кнопка "Скинути вибір"
        if (e.target.classList.contains('deselect-all-kndk-btn')) {
            // Скидаємо галочки абсолютно з усіх КНДК у цій картці
            const allCheckboxes = card.querySelectorAll('.kndk-checkbox');
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    });

    // Переіндексація масивів після видалення картки
    function reindexCards() {
        const cards = container.querySelectorAll('.risk-card');
        currentCount = cards.length;
        
        cards.forEach((card, index) => {
            card.setAttribute('data-index', index);
            card.querySelector('h5').innerHTML = `#${index + 1} {{ __('Ризик') }}`;
            
            card.querySelector('input[type="text"]').setAttribute('name', `risks[${index}][name]`);
            const textarea = card.querySelector('textarea');
            if (textarea) textarea.setAttribute('name', `risks[${index}][description]`);

            const checkboxes = card.querySelectorAll('.kndk-checkbox');
            checkboxes.forEach(checkbox => {
                const vId = checkbox.value;
                checkbox.setAttribute('name', `risks[${index}][kndk_ids][]`);
                checkbox.setAttribute('id', `kndk_${index}_${vId}`);
                checkbox.nextElementSibling.setAttribute('for', `kndk_${index}_${vId}`);
            });
        });
        
        toggleDeleteButtons();
    }

    function toggleDeleteButtons() {
        const cards = container.querySelectorAll('.risk-card');
        const deleteButtons = container.querySelectorAll('.delete-risk-btn');
        
        deleteButtons.forEach(btn => {
            btn.style.display = cards.length > 1 ? 'block' : 'none';
        });
    }
});


    </script>         
@endsection
