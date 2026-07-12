@extends('layouts.app')
@section('content')

<div class="container py-4" style="max-width: 900px;">
   
    <!-- Заголовок -->
    <div class="mb-4">
        <a href="{{ route('kndks.index') }}" class="text-decoration-none text-muted small">
            &larr; Повернутися до списку
        </a>
        <h1 class="h3 mt-2 mb-1">Керування процесами та зв'язками КНДК</h1>
        <p class="text-muted">Налаштування зв'язків процесу з КНДК, підрозділами та посадами</p>
    </div>

    <!-- Картка форми -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('kndks_pocedure.store') }}" method="POST" id="processCreateForm">
                @csrf
                
                    <h5 class="text-primary mb-3 border-bottom pb-2">📂 Основна інформація про процес</h5>
                    <div class="row g-3 mb-4">
                        <!-- Ліва колонка: Назва процесу -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Назва процесу <span id="name_required_star" class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                value="" placeholder="Введіть назву нового процесу або залиште порожнім">
                            <div class="form-text text-muted small">
                                Якщо залишити порожнім, новий процес не створиться, а прив'язка піде прямо до КНДК.
                            <br>{{ old('name') }}
                            </div>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Права колонка: Тип процесу -->
                        <div class="col-md-6">
                            <label for="process_type" class="form-label fw-semibold">
                                Тип процесу <span class="text-danger">*</span>
                            </label>
                            <select name="process_type" id="process_type" class="form-select @error('process_type') is-invalid @enderror" required>
                                <option value="" disabled {{ !old('process_type') ? 'selected' : '' }}>Оберіть тип процесу...</option>
                                <option value="inputs" {{ old('process_type') == 'inputs' ? 'selected' : '' }}>Входи процесу</option>
                                <option value="resources" {{ old('process_type') == 'resources' ? 'selected' : '' }}>Ресурси/управлінські впливи</option>
                                <option value="outputs" {{ old('process_type') == 'outputs' ? 'selected' : '' }}>Виходи процесу</option>
                                <option value="tasks" {{ old('process_type') == 'tasks' ? 'selected' : '' }}>Основні завдання</option>
                                <option value="results" {{ old('process_type') == 'results' ? 'selected' : '' }}>Результат/основна звітність</option>
                                <option value="performance" {{ old('process_type') == 'performance' ? 'selected' : '' }}>Показники результативності</option>
                                <option value="corporate_requirements" {{ old('process_type') == 'corporate_requirements' ? 'selected' : '' }}>Загальнокорпоративні вимоги (комплаєнс)</option>

                            </select>
                            <div class="form-text text-muted small">Оберіть категорію, до якої належить цей елемент процесу.</div>
                            @error('process_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                                <!-- Нижня колонка: Опис процесу -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold">Опис процесу</label>
                        <input type="hidden" name="process_id"  id="process_id" value="">
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                rows="3" placeholder="Детальний опис кроків чи регламенту процесу..."></textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        
                        <!-- Кнопка пошуку подібних -->
                        <button type="button" id="btn-find-similar" class="btn btn-outline-secondary btn-sm mt-2">
                            🔍 Знайти подібні процеси
                        </button>
                    </div>

                    <!-- Модальне вікно (Bootstrap 5) -->
                    <div class="modal fade" id="similarProcessesModal" tabindex="-1" aria-labelledby="similarModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="similarModalLabel">Знайдені подібні процеси</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="list-group" id="similar-processes-list">
                                        <!-- Сюди за допомогою JS будуть додаватися результати -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-primary mb-3 border-bottom pb-2">🔗 Налаштування зв'язків</h5>
                    <div class="row g-3">
                        <!-- Зв'язок з КНДК (Багато до багато) -->
                        <div class="col-12">
                           <label for="kndk_ids" class="form-label fw-semibold">
                                Пов'язані елементи КНДК (СОУ НАЕК 180:2020)
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                id="search_kndk"
                                class="form-control mb-2"
                                placeholder="Пошук КНДК..."
                            >

                            <select
                                name="kndk_ids[]"
                                id="kndk_ids"
                                class="form-select @error('kndk_ids') is-invalid @enderror"
                                multiple
                                required
                                style="min-height: 150px;"
                            >               
                                 @foreach($kndks as $kndk)
                                    @php
                                        $code = $kndk->class;
                                        if($kndk->subclass) $code .= '.' . $kndk->subclass;
                                        if($kndk->group) $code .= '.' . $kndk->group;
                                    @endphp
                                    <option value="{{ $kndk->id }}" {{ (is_array(old('kndk_ids')) && in_array($kndk->id, old('kndk_ids'))) ? 'selected' : '' }}>
                                        [{{ $code }}] {{ Str::limit($kndk->name, 90) }} (Документів: {{ $kndk->documents_count }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">Утримуйте <kbd>Ctrl</kbd> (або <kbd>Cmd</kbd> на Mac) для вибору кількох елементів.</div>
                            @error('kndk_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Ліва колонка: Власник процесу -->
                        <div class="col-md-6">
                            <div class="card h-100 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold text-secondary mb-3">Власник процесу</h6>
                                   <label for="position_own_ids"
                                        class="form-label fw-semibold">
                                        Відповідальні посади (власники)
                                    </label>

                                    <input
                                        type="text"
                                        id="search_position_owner"
                                        class="form-control mb-2"
                                        placeholder="Пошук посади..."
                                    >

                                    <select
                                        name="position_own_ids[]"
                                        id="position_own_ids"
                                        class="form-select"
                                        multiple
                                        style="min-height: 280px;"
                                    >
                                        @foreach($Bosspositions as $position)
                                            <option value="{{ $position->id }}" {{ (is_array(old('position_own_ids')) && in_array($position->id, old('position_own_ids'))) ? 'selected' : '' }}>
                                                [{{ $position->abv }}] {{ $position->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text text-muted small">Утримуйте <kbd>Ctrl</kbd> або <kbd>Cmd</kbd> для вибору кількох.</div>
                                    @error('position_own_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Права колонка: Основні учасники процесу -->
                        <div class="col-md-6">
                            <div class="card h-100 bg-light-subtle">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold text-secondary mb-3">Основні учасники процесу</h6>
                                    
                                    <!-- Підрозділи -->
                                    <div class="mb-3">
                                       <label for="division_ids"
                                                class="form-label fw-semibold">
                                                Відповідальні підрозділи
                                            </label>

                                            <input
                                                type="text"
                                                id="search_division"
                                                class="form-control mb-2"
                                                placeholder="Пошук підрозділу..."
                                            >

                                            <select
                                                name="division_ids[]"
                                                id="division_ids"
                                                class="form-select"
                                                multiple
                                                style="min-height: 100px;"
                                            >
                                            @foreach($rootDivisions as $division)
                                                <option value="{{ $division->id }}" {{ (is_array(old('division_ids')) && in_array($division->id, old('division_ids'))) ? 'selected' : '' }}>
                                                    {{ $division->name }} {{ $division->abv ? "({$division->abv})" : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('division_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <!-- Посади -->
                                    <div>
                                       <label for="position_ids"
                                        class="form-label fw-semibold">
                                        Відповідальні посади
                                        </label>

                                        <input
                                        type="text"
                                        id="search_position"
                                        class="form-control mb-2"
                                        placeholder="Пошук посади..."
                                        >

                                        <select
                                        name="position_ids[]"
                                        id="position_ids"
                                        class="form-select"
                                        multiple
                                        style="min-height: 100px;"
                                        >
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" {{ (is_array(old('position_ids')) && in_array($position->id, old('position_ids'))) ? 'selected' : '' }}>
                                                    [{{ $position->abv }}] {{ $position->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text text-muted small">Утримуйте <kbd>Ctrl</kbd> або <kbd>Cmd</kbd> для вибору кількох.</div>
                                        @error('position_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">{{ __('Документ') }} </h4> 
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input 
                                            type="text" 
                                            id="search" 
                                            class="form-control" 
                                            placeholder="Введіть шифр, інв. номер або організацію"
                                            value="{{ old('search_text', isset($selectedDocument) ? "[{$selectedDocument->code}] {$selectedDocument->title}" : '') }}"
                                        >
                                        <!-- Приховане поле для збереження тексту при помилці валідації -->
                                        <input type="hidden" name="search_text" id="search_text_hidden" value="{{ old('search_text', isset($selectedDocument) ? "[{$selectedDocument->code}] {$selectedDocument->title}" : '') }}">
                                         <input type="hidden" name="document_id" id="doc_id" value="{{ old('document_id', $selectedDocument->id ?? '') }}">
                           
                                    </div>                               
                                <ul id="results" class="list-group"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-4">

                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">{{__('ключові слова')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <textarea name="keywords" id="keywords" class="form-control @error('keywords') is-invalid @enderror" 
                                    rows="2" placeholder="Введіть ключові слова для цього процесу...">{{ old('keywords') }}</textarea>

                                <div class="form-text text-muted small">
                                    Введіть ключові слова, розділяючи їх комами. Це допоможе швидко знаходити процес у майбутньому.
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="generateKeywordsBtn">Згенерувати з назви та опису</button>  
                                @error('keywords') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                <!-- Кнопки дій -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('kndks.index') }}" class="btn btn-light px-4">Скасувати</a>
                    <button type="submit" id="submitBtn" class="btn w-100 btn-success px-4">Зберегти зв'язки</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript секція для інтерактивності --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnFind = document.getElementById('btn-find-similar');
    const textareaDesc = document.getElementById('description');
    const modalElement = document.getElementById('similarProcessesModal');
    const modalList = document.getElementById('similar-processes-list');    
    // Ініціалізація модального вікна Bootstrap
    const bsModal = new bootstrap.Modal(modalElement);
    // Масив для тимчасового зберігання знайдених процесів
    let foundProcesses = [];
    btnFind.addEventListener('click', function (event) {
        // Зупиняємо стандартну відправку форми
        event.preventDefault();
        event.stopPropagation();
        const textValue = textareaDesc.value.trim();
        if (!textValue) {
            alert('Будь ласка, введіть текст в опис процесу для пошуку!');
            return;
        }
        // Блокуємо кнопку на час запиту
        btnFind.disabled = true;
        btnFind.innerHTML = '⏳ Шукаємо...';
        // Кодуємо текст для GET-запиту
        const encodedText = encodeURIComponent(textValue);
        const baseUrl = "{{ route('processes.search_similar') }}";
        const finalUrl = `${baseUrl}?d=${encodedText}`;
        fetch(finalUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Помилка сервера: ' + response.status);
            }
            return response.json();
        })
        .then(res => {
            modalList.innerHTML = ''; // Очищаємо список у модалці
            
            if (res.success && res.data && res.data.length > 0) {
                foundProcesses = res.data; // Оновлюємо масив глобально для цього scope
                res.data.forEach((process, index) => {
                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'list-group-item list-group-item-action text-start p-3';
                    // Безпечно перевіряємо, чи є документ у масиві, щоб уникнути збоїв JS
                    const doc = process.documents && process.documents.length > 0 ? process.documents[0] : null;

                    item.innerHTML = `
                        <div class="d-flex w-100 justify-content-between align-items-start gap-2 mb-2">
                            <!-- Назва процесу -->
                            <h6 class="mb-0 fw-bold text-primary" style="flex: 1;">${process.name}</h6>
                            
                            <!-- Бейджі типу та документа -->
                            <div class="d-flex flex-column align-items-end gap-1" style="white-space: nowrap;">
                                <small class="badge bg-secondary">${process.type || 'Не вказано'}</small>
                                
                                <!-- Бейдж документа виводиться лише за його наявності -->
                                ${doc ? `
                                    <small class="badge bg-info text-dark text-wrap text-end" style="max-width: 250px;">
                                        ${doc.code || ''} ${doc.short_content || ''} ${doc.organization || ''}
                                    </small>
                                ` : ''}
                            </div>
                        </div>
                        
                        <!-- Опис процесу -->
                        <p class="mb-0 text-muted small text-truncate-2">
                            ${process.description ? process.description.substring(0, 150) + '...' : ''}
                        </p>
                    `;                
                    // Подія натискання на процес у списку
                    item.addEventListener('click', function () {
                        selectProcess(index);
                    });
                    modalList.appendChild(item);
                });
                bsModal.show(); // Відкриваємо модалку
            } else {
                alert('Подібних процесів не знайдено.');
            }
        })
        .catch(error => {
            console.error('Помилка AJAX:', error);
            alert('Сталася помилка при пошуку: ' + error.message);
        })
        .finally(() => {
            btnFind.disabled = false;
            btnFind.innerHTML = '🔍 Знайти подібні процеси';
        });
    });

    // Функція, яка заповнює форму обраним процесом та дублює посади
    function selectProcess(index) {
        const process = foundProcesses[index];
        if (!process) return;

        // 1. Заповнюємо основні текстові поля
        if (document.getElementById('name')) {
            document.getElementById('name').value = process.name || '';
        }
        
        // Заповнюємо тип процесу
        const typeSelect = document.getElementById('process_type');
        if (typeSelect) {
            typeSelect.value = process.type || '';
            typeSelect.dispatchEvent(new Event('change'));
        }
        
        // Заповнюємо опис процесу
        if (textareaDesc) {
            textareaDesc.value = process.description || '';
        }
       // process_id
        document.getElementById('process_id').value = process.id;
        // 2. Автозаповнення даних документа (зв'язок 'documents')
        if (process.documents && process.documents.length > 0) {
            const firstDoc = process.documents[0]; // Беремо перший документ із колекції
            console.log(firstDoc);
            if (document.getElementById('doc_id')) {
                document.getElementById('doc_id').value = firstDoc.inv_no;
            }
            if (document.getElementById('search')) {
                document.getElementById('search').value = firstDoc.short_content;
                document.getElementById('search_text_hidden').value = firstDoc.short_content;
            }
        } else {
            if (document.getElementById('doc_id')) document.getElementById('doc_id').value = '';
            if (document.getElementById('search')) document.getElementById('search_text').value = '';
        }

        // --- ЗБИРАЄМО ВСІ СЕЛЕКТОРИ НА ФОРМІ ДЛЯ СКИДАННЯ ТА ЗАПОВНЕННЯ ---
        const kndkInputs = document.querySelectorAll('input[name="kndk_ids[]"], select[name="kndk_ids[]"] option');
        const divisionInputs = document.querySelectorAll('input[name="division_ids[]"], select[name="division_ids[]"] option');
        
        // Поля власних посад та посад КНДК
        const posOwnInputs = document.querySelectorAll('input[name="position_own_ids[]"], select[name="position_own_ids[]"] option');
        const posInputs = document.querySelectorAll('input[name="position_ids[]"], select[name="position_ids[]"] option');

        // 3. Скидаємо всі раніше обрані чекбокси/селекти
        const allInputs = [...kndkInputs, ...divisionInputs, ...posOwnInputs, ...posInputs];
        allInputs.forEach(input => {
            if (input.tagName === 'INPUT') input.checked = false;
            if (input.tagName === 'OPTION') input.selected = false;
        });

        // 4. Заповнюємо КНДК та збираємо їхні посади
        if (process.kndks && process.kndks.length > 0) {
            const kndkIds = process.kndks.map(k => k.id.toString());
            kndkInputs.forEach(input => {
                if (kndkIds.includes(input.value.toString())) {
                    if (input.tagName === 'INPUT') input.checked = true;
                    if (input.tagName === 'OPTION') input.selected = true;
                }
            });
            
            // 
                   // 1. Створюємо окремі масиви для автоматичних ID
        let autoPositionIds = [];    // Для виконавців (executors)
        let autoPositionOwnIds = []; // Для власників (owners)

        if (process.kndks && process.kndks.length > 0) {
                process.kndks.forEach(kndk => {
                    if (kndk.positions && kndk.positions.length > 0) {
                        kndk.positions.forEach(pos => {
                            // Перевіряємо роль через pivot-таблицю Laravel
                            if (pos.pivot && pos.pivot.role === 'owner') {
                                autoPositionOwnIds.push(pos.id.toString());
                            } else {
                                // За замовчуванням або якщо роль 'executor'
                                autoPositionIds.push(pos.id.toString());
                            }
                        });
                    }
                });

                // 2. Автозаповнення для виконавців (position_ids)
                posInputs.forEach(input => {
                    if (autoPositionIds.includes(input.value.toString())) {
                        if (input.tagName === 'INPUT') input.checked = true;
                        if (input.tagName === 'OPTION') input.selected = true;
                    }
                });

                // 3. Автозаповнення для власників (position_own_ids) - тепер суто своїми ID
                posOwnInputs.forEach(input => {
                    if (autoPositionOwnIds.includes(input.value.toString())) {
                        if (input.tagName === 'INPUT') input.checked = true;
                        if (input.tagName === 'OPTION') input.selected = true;
                    }
                });
            }

        }

        // 7. Заповнюємо підрозділи (division_ids)
        if (process.divisions && process.divisions.length > 0) {
            const divisionIds = process.divisions.map(d => d.id.toString());
            divisionInputs.forEach(input => {
                if (divisionIds.includes(input.value.toString())) {
                    if (input.tagName === 'INPUT') input.checked = true;
                    if (input.tagName === 'OPTION') input.selected = true;
                }
            });
        }
        console.log(process);
        if (process.keywords && process.keywords.length > 0) {
            console.log(process.keywords);
            // Збираємо масив імен ключових слів і склеюємо їх через кому з пробілом
            const keywordsString = process.keywords.map(k => k.name).join(', ');
            console.log(keywordsString);
            if (document.getElementById('keywords')) {
                document.getElementById('keywords').value = keywordsString;
            }
        } else {
            if (document.getElementById('keywords')) {
                document.getElementById('keywords').value = '';
            }
        }
        // 8. Оновлюємо візуальний стан мультиселектів (Select2 тощо)
        const multiSelects = document.querySelectorAll('select[name="kndk_ids[]"], select[name="division_ids[]"], select[name="position_own_ids[]"], select[name="position_ids[]"]');
        multiSelects.forEach(select => select.dispatchEvent(new Event('change')));

        // Закриваємо модальне вікно
        bsModal.hide();
    }



});

document.addEventListener('DOMContentLoaded', function () {
    
    // ==========================================
    // 1. УНІВЕРСАЛЬНИЙ ЛАЙВ-ПОШУК ДЛЯ СЕЛЕКТІВ
    // ==========================================
    function initLiveSearch(inputId, selectId) {
        const searchInput = document.getElementById(inputId);
        const selectElement = document.getElementById(selectId);
        
        if (!searchInput || !selectElement) return;

        const options = Array.from(selectElement.options);

        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();

            options.forEach(option => {
                const optionText = option.text.toLowerCase();

                if (option.selected) {
                    option.style.display = '';
                    return;
                }

                if (optionText.includes(searchTerm)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
        });
    }

    // Ініціалізуємо лайв-пошук для всіх списків на сторінці
    initLiveSearch('search_position_owner', 'position_own_ids'); // Власник: Посади
    initLiveSearch('search_division', 'division_ids');           // Учасники: Підрозділи
    initLiveSearch('search_position', 'position_ids');           // Учасники: Посади
    initLiveSearch('search_kndk', 'kndk_ids');                   // Зв'язок з КНДК

    // ==========================================
    // 2. ДИНАМІЧНИЙ АЯКС-ПОШУК ДОКУМЕНТІВ (FETCH)
    // ==========================================
    const resultsContainer = document.getElementById('results');
    const searchDocInput = document.getElementById('search');

    function performSearch(query) {
        if (!resultsContainer || query.length < 3) return;

        fetch("{{ route('inconsistencis.searchdoc') }}?q=" + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                resultsContainer.innerHTML = '';
                data.forEach(doc => {
                    let li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action';
                    
                    let displayText = '(' + doc.inv_no + ') ' + doc.short_content + ' (' + doc.code + ')';
                    li.textContent = displayText;
                    
                    // Зберігаємо дані в нижньому регістрі атрибутів DOM
                    li.dataset.invno = doc.inv_no; 
                    li.dataset.text = displayText;

                    resultsContainer.appendChild(li);
                });
            })
            .catch(err => console.error('Помилка пошуку документів:', err));
    }

    // Слухач введення тексту для пошуку документів
    if (searchDocInput) {
        searchDocInput.addEventListener('input', function() {
            performSearch(this.value);
        });
    }

    // Обробник вибору документа зі списку результатів (Делегування подій)
    if (resultsContainer) {
        resultsContainer.addEventListener('click', function(e) {
            const li = e.target.closest('li');
           
            if (!li) return;

            const invno = li.dataset.invno; 
            const docText = li.dataset.text;
 
            var documentInput = document.getElementById('doc_id').value = invno;
            

            if (searchDocInput) {
                searchDocInput.value = docText;
            }
            
            const hiddenTextInput = document.getElementById('search_text_hidden');
            if (hiddenTextInput) {
                hiddenTextInput.value = docText;
            }

            resultsContainer.innerHTML = '';
        });
    }

});

document.getElementById('generateKeywordsBtn').addEventListener('click', function(e) {
    let descriptionText = document.getElementById('description').value || '';
    
    // 1. Очищаємо текст від специфічних символів
    // Додано обов'язкове очищення від дужок (, ), лапок та інших знаків
    let cleanedText = descriptionText.replace(/[:;()"'«».,!?;\-\[\]\{\}\/]/g, ' ');

    // 2. Розбиваємо текст на окремі слова по пробілах та очищаємо порожні елементи
    let words = cleanedText.split(/\s+/).filter(Boolean);

    // Список стоп-слів, які потрібно ігнорувати (у нижньому регістрі)
    const stopWords = [
        'яка', 'про', 'хаес','час', 'крім', 'при', 'від', 'для', 'неї', 'інші','них', 'всіх', 'своєчасне','часу','усіх','вимог',
        'цих', 'вже', 'через', 'після', 'його',  'чинного', 'зокрема',  'метою', 'під','наек', 'енергоатом',  'або',  'яких' , 'разі', 'інших', 
        'всієї', 'щодо', 'іншим', 'такому', 'буде',  'такі', 'якого',  'також', 'тощо', 'згідно', 'саме', 'більш',  'мірі' , 'який' , 'тому','перед', 'числі' ,'які' 
    ];

    // 3. Фільтруємо масив слів за вашими правилами
    let filteredWords = words.filter(word => {
        // Приводимо до нижнього регістру перед фільтрацією для точного порівняння
        let lowerWord = word.toLowerCase();

        // Перевіряємо довжину слова (повинно бути 3 або більше символів, як у вашому запиті)
        // Якщо потрібно від 4 символів, замініть на < 4
        if (lowerWord.length < 3) return false;

        // Перевіряємо на наявність цифр
        if (/\d/.test(lowerWord)) return false;

        // Видаляємо стоп-слова, які не несуть смислового навантаження
        if (stopWords.includes(lowerWord)) return false;

        return true;
    });

    // 4. Приводимо залишок слів до нижнього регістру
    let lowerCasedWords = filteredWords.map(word => word.toLowerCase());

    // 5. Видаляємо дублікати за допомогою Set
    let uniqueWords = [...new Set(lowerCasedWords)];

    // 6. Записуємо результат у поле keywords через кому з пробілом
    document.getElementById('keywords').value = uniqueWords.join(', ');
});
</script>
@endsection
