@extends('layouts.app') 

@section('content') 
@php
    $form = session('form_data', []);
     //  dd($form);
@endphp
<div class="container py-4" style="max-width: 900px;"> <!-- Заголовок --> 
    <div class="mb-4"> 
        <a href="{{ route('kndks.index') }}" class="text-decoration-none text-muted small"> &larr; Повернутися до списку </a> 
        <h1 class="h3 mt-2 mb-1">Керування процесами та зв'язками КНДК</h1> 
        <p class="text-muted">Налаштування зв'язків процесу з КНДК, підрозділами та посадами</p> 
    </div> 
    <!-- Форма введення тексту -->
    <div class="card p-4 shadow-sm mb-4">
        <div class="mb-3">
            <label for="textInput" class="form-label fw-bold">Вставте ваш текст сюди:</label>
            <textarea class="form-control" id="textInput" rows="8" placeholder="Введіть або вставте текст з кількома абзацами..."></textarea>
        </div>
         <!-- Тип процесу -->
            <div class="col-md-12">
                <label for="process_type" class="form-label fw-semibold">
                    Тип процесу <span class="text-danger">*</span>
                </label>
                <x-select-process-type 
                :options="$processTypes"   
                :selected="$form['process_type'] ?? old('process_type')" 
                />
                <div class="form-text text-muted small">Оберіть категорію, до якої належить цей елемент процесу.</div>
                @error('process_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                <x-multi-select
                    id="kndk_ids"
                    name="kndk_ids"
                    :options="$kndks->map(fn($kndk) => [
                        'id' => $kndk->id,
                        'text' => '[' . $kndk->class . ($kndk->subclass ? '.' . $kndk->subclass : '') . ($kndk->group ? '.' . $kndk->group : '') . '] ' . Str::limit($kndk->name, 90) . ' (Документів: ' . $kndk->documents_count . ')'
                    ])->toArray()"
                     :selected="$form['kndk_ids'] ?? old('kndk_ids', [])"
                  
                    required="true"
                    height="150px"
                />
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
                            <x-multi-select
                            id="position_own_ids"
                            name="position_own_ids"
                            :options="$Bosspositions->map(fn($position) => [
                                'id' => $position->id,
                                'text' => '[' . $position->abv . '] ' . $position->name
                            ])->toArray()"
                            :selected="$form['position_own_ids'] ?? old('position_own_ids', [])"
                            required="false"
                            height="280px"
                        />                        
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
                                    <x-multi-select
                                    id="division_ids"
                                    name="division_ids"
                                    :options="$rootDivisions->map(fn($division) => [
                                        'id' => $division->id,
                                        'text' => $division->name . ($division->abv ? ' (' . $division->abv . ')' : '')
                                    ])->toArray()"
                                      :selected="$form['division_ids'] ?? old('division_ids', [])"
                                    required="false"
                                    height="100px"
                                />
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

                            <x-multi-select
                                id="position_ids"
                                name="position_ids"
                                :options="$positions->map(fn($position) => [
                                    'id' => $position->id,
                                    'text' => '[' . $position->abv . '] ' . $position->name
                                ])->toArray()"
                                 :selected="$form['position_ids'] ?? old('position_ids', [])"
                                required="false"
                                height="100px"
                            />  
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
                              value="{{ old('search_text', $form['doc_name'] ?? '') }}"
                                  >
                            <!-- Приховане поле для збереження тексту при помилці валідації -->
                            <input type="hidden" name="search_text" id="search_text_hidden"  value="{{ old('search_text', $form['doc_name'] ?? '') }}">
                            <input type="hidden" name="document_id" id="doc_id" value="{{ old('document_id', $form['doc_id'] ?? '') }}">
                
                        </div>                               
                    <ul id="results" class="list-group"></ul>
                </div>
            </div>
        </div>
        <button type="button" id="parseButton" class="btn btn-primary w-100">
            Розбити на абзаци та вивести в консоль
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
     <!-- 2. Головна форма для відправки скопом -->
    <form action="{{ route('kndks.massStore') }}" method="POST" id="massProcessForm" style="display: none;">
        @csrf
         <div class="card p-3 shadow-sm bg-light text-end mb-5">  <!-- Кнопка загального сабміту -->
            <button type="submit" class="btn btn-success btn-lg px-5 fw-bold">
                Зберегти всі процеси спільно (Submit)
            </button>
        </div>
        <input   type="text" id="doc0_name"  name="doc0_name" class="form-control"  >
        <input type="hidden" name="document0_id" id="doc0_id" value="">
        <!-- Контейнер, куди JS додаватиме блоки абзаців -->
        <div id="paragraphsContainer"></div>
      </form>
  
</div> 

<!-- ХОВАНИЙ ШАБЛОН ДЛЯ СЕЛЕКТІВ (Використовується JavaScript для генерації) -->
<div id="selectTemplates" style="display: none;">
     <x-select-process-type :options="$processTypes"/>
    <!-- Селект КНДК -->
    <x-multi-select
        id="kndk_ids"
        name="kndk_ids"
        :options="$kndks->map(fn($kndk) => [
            'id' => $kndk->id,
            'text' => '[' . $kndk->class . ($kndk->subclass ? '.' . $kndk->subclass : '') . ($kndk->group ? '.' . $kndk->group : '') . '] ' . Str::limit($kndk->name, 90)
        ])->toArray()"
        required="false"
        height="150px"
    />
    <!-- Селект Підрозділів -->
    <x-multi-select
        id="division_ids"
        name="division_ids"
        :options="$rootDivisions->map(fn($division) => [
            'id' => $division->id,
            'text' => $division->name
        ])->toArray()"
        required="false"
        height="100px"
    />
    
    <!-- Селект Керівних посад -->
     <x-multi-select
        id="position_own_ids"
        name="position_own_ids"
        :options="$Bosspositions->map(fn($position) => [
            'id' => $position->id,
            'text' => '[' . $position->abv . '] ' . $position->name
        ])->toArray()"
        required="false"
        height="280px"
    />
    <!-- Селект Звичайних посад -->
    <x-multi-select
        id="position_ids"
        name="position_ids"
        :options="$positions->map(fn($position) => [
            'id' => $position->id,
            'text' => $position->name
        ])->toArray()"
        required="false"
        height="100px"
    />
</div>
{{-- JavaScript секція для інтерактивності --}} 
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Елементи інтерфейсу
    const parseButton = document.getElementById('parseButton');
    const textInput = document.getElementById('textInput');
    const massProcessForm = document.getElementById('massProcessForm');
    const paragraphsContainer = document.getElementById('paragraphsContainer');
    const basekndkId = @json($kndkId);
    const select = document.getElementById('kndk_ids');
    if (select && basekndkId) {
        // Якщо це масив id
        if (Array.isArray(basekndkId)) {
            basekndkId.forEach(id => {
                const option = select.querySelector(`option[value="${id}"]`);
                if (option) option.selected = true;
            });
        } else {
            // Якщо це одиночне значення
            const option = select.querySelector(`option[value="${basekndkId}"]`);
            if (option) option.selected = true;
        }
    }
    // ==========================================
    // 1. ОЧИЩЕННЯ ТА ЗБІР КЛЮЧОВИХ СЛІВ
    // ==========================================
    function extractKeywords(text) {
        if (!text) return '';
        let cleaned = text.replace(/[:;()"'«».,!?;\-\[\]\{\}\/]/g, ' ');
        let words = cleaned.split(/\s+/).filter(Boolean);
      const stopWords = [
        'а', 'або', 'але', 'багато', 'би', 'біля', 'бо', 'більш', 'буде', 'будемо', 
        'будете', 'будешь', 'буди', 'була', 'були', 'було', 'бути', 'в', 'вже', 'ви', 
        'вимог', 'він', 'від', 'відповідно', 'вона', 'вони', 'воно', 'всі', 'всій', 
        'всіх', 'всієї', 'всіма', 'всьому', 'всупереч', 'де', 'для', 'до', 'доки', 
        'дуже', 'енергатом', 'енергоатом', 'є', 'за', 'завдяки', 'загалом', 'зараз', 
        'згідно', 'зі', 'зокрема', 'й', 'його', 'йому', 'і', 'із', 'інша', 
        'інше', 'інши', 'інших', 'іншим', 'іншими', 'інші', 'категорично', 'коли', 
        'коло', 'котрий', 'крім', 'куди', 'лише', 'має', 'мають', 'майже', 'мало', 
        'мене', 'метою', 'ми', 'між', 'мірі', 'мій', 'може', 'можуть', 'мов', 
        'на', 'над', 'навіть', 'наек', 'нам', 'нами', 'нас', 'наш', 'наша', 
        'наше', 'наші', 'наче', 'не', 'неї', 'нехай', 'нижче', 'них', 'ні', 
        'ніби', 'ніж', 'ніхто', 'нічого', 'ну', 'о', 'об', 'обов\'язково', 'обмежено', 
        'один', 'одна', 'однак', 'одне', 'одні', 'ось', 'офіційно', 'перед', 'під', 
        'після', 'по', 'поки', 'потім', 'при', 'про', 'проте', 'протягом', 'разі', 
        'разом', 'рік', 'років', 'року', 'році', 'саме', 'свій', 'своє', 'своєчасне', 
        'свої', 'своїх', 'себе', 'собою', 'та', 'так', 'така', 'таке', 'такі', 
        'такого', 'такому', 'також', 'там', 'твій', 'те', 'теж', 'ти', 'тим', 
        'тисяч', 'ті', 'тільки', 'то', 'тоді', 'того', 'тож', 'тому', 'тощо', 
        'треба', 'тут', 'у', 'усі', 'усіх', 'усьому', 'хаес', 'хай', 'хто', 
        'це', 'цей', 'ця', 'цих', 'цим', 'цими', 'ці', 'час', 'часу', 'через', 
        'чи', 'чий', 'чинного', 'числі', 'що', 'щоб', 'щодо', 'ще', 'я', 
        'яка', 'який', 'якого', 'якому', 'яких', 'які', 'якість', 'як', 'якби', 
        'якщо'
        ];

        let filtered = words.filter(word => {
            let lw = word.toLowerCase();
            return lw.length >= 3 && !/\d/.test(lw) && !stopWords.includes(lw);
        });
        return [...new Set(filtered.map(w => w.toLowerCase()))].join(', ');
    }
    // ==========================================
    // 2. ГЕНЕРАЦІЯ НАЗВИ З ЦІЛИХ СЛІВ
    // ==========================================
   function generateTitle(text) {
        if (!text) return '';

        // Видаляємо з початку рядка цифри, пробіли та символи: . , ( ) : ; _ -
        // ^[\d\s.,():;_\-]+ означає шукати ці символи виключно на самому початку рядка
        let cleanedText = text.replace(/^[\d\s.,():;_\-]+/, '').trim();

        // Якщо після очищення текст коротший або дорівнює 150 символів
        if (cleanedText.length <= 150) return cleanedText;

        // Якщо довший, обрізаємо по цілих словах
        let sliced = cleanedText.slice(0, 150);
        let lastSpace = sliced.lastIndexOf(' ');
        if (lastSpace > 0) {
            sliced = sliced.slice(0, lastSpace);
        }
        return sliced.trim() + '...';
    }
    // ==========================================
    // 3. ЗЧИТУВАННЯ ДАНИХ З ОПОРНИХ (ВЕРХНІХ) БЛОКІВ
    // ==========================================
    function getMasterData() {
        const getSelected = (id) => {
            const el = document.getElementById(id);
            return el ? Array.from(el.selectedOptions).map(opt => opt.value) : [];
        };
        return {
            type: document.getElementById('process_type')?.value || '',
            kndk: getSelected('kndk_ids'),
            owner: getSelected('position_own_ids'),
            executor: getSelected('position_ids'),
            division: getSelected('division_ids')
        };
    }

    // ==========================================
    // 5. ІНІЦІАЛІЗАЦІЯ ЛАЙВ-ПОШУКУ ДЛЯ СЕЛЕКТУ
    // ==========================================
    function initCardLiveSearch(card, inputSelector, selectElement) {
        const input = card.querySelector(inputSelector);
        if (!input || !selectElement) return;

        const options = Array.from(selectElement.options);
        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            options.forEach(opt => {
                opt.style.display = opt.selected || opt.text.toLowerCase().includes(query) ? '' : 'none';
            });
        });
    }
    function createSelect(masterElementId, name, container, masterValues, isMultiple = false) {
        const masterSelect = document.getElementById(masterElementId);        
        if (!masterSelect) {
            console.error(`Помилка: Не знайдено опорний селект з id="${masterElementId}"!`);
            return null;
        }
        // Клонуємо верхній опорний селект
        const select = masterSelect.cloneNode(true);
        
        // Видаляємо ID, щоб уникнути дублікатів у DOM
        select.removeAttribute('id');
        select.name = name;
        select.className = 'form-select';
        
        // Відновлюємо вибір на основі опорного елемента
        if (isMultiple) {
            Array.from(select.options).forEach(opt => {
                opt.selected = masterValues.includes(opt.value);
                opt.style.display = ''; // Скидаємо приховання від лайв-пошуку
            });
        } else if (masterValues) {
            select.value = masterValues;
        }

        container.appendChild(select);
        return select;
    }
    // СТВОРЕННЯ HTML-КАРТКИ ДЛЯ АБЗАЦУ
    // ==========================================
    function createParagraphCard(paragraph, index, master) {
        const title = generateTitle(paragraph);
        const keywords = extractKeywords(paragraph);
        const card = document.createElement('div');
        card.className = 'card shadow-sm mb-4 border-primary';
        card.innerHTML = `
            <div class="card-header bg-primary text-white fw-bold">Процес/Абзац №${index + 1}</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">Згенерована назва:</label>
                    <input type="text" name="paragraphs[${index}][title]" class="form-control fw-bold" value="${title.replace(/"/g, '&quot;')}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">Ключові слова:</label>
                    <input type="text" name="paragraphs[${index}][keywords]" class="form-control text-primary" value="${keywords.replace(/"/g, '&quot;')}">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold text-muted small">Повний текст:</label>
                    <textarea name="paragraphs[${index}][full_text]" class="form-control bg-light" rows="3">${paragraph}</textarea>
                </div>
                <div class="col-md-12 mb-4">
                    <label class="form-label fw-semibold">Тип процесу <span class="text-danger">*</span></label>
                    <div class="process-type-container"></div>
                </div>
                <h5 class="text-primary mb-3 border-bottom pb-2">🔗 Налаштування зв'язків</h5>
                <div class="row g-3">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Пов'язані elements КНДК <span class="text-danger">*</span></label>
                        <input type="text" class="form-control mb-2 search-kndk" placeholder="Пошук КНДК...">
                        <div class="kndk-select-container"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Відповідальні посади (власники)</label>
                        <input type="text" class="form-control mb-2 search-owner" placeholder="Пошук посади...">
                        <div class="boss-select-container"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Відповідальні підрозділи</label>
                        <input type="text" class="form-control mb-2 search-division" placeholder="Пошук підрозділу...">
                        <div class="division-select-container"></div>
                    </div>
                     <div class="col-md-6">
                        <label class="form-label fw-semibold">Відповідальні посади</label>
                       <input type="text"    class="form-control mb-2 search-executor" placeholder="Пошук посади...">
                        <div class="executor-select-container"></div>
                    </div>
                </div>
            </div>`;

        // Клонуємо та зв'язуємо списки з id верхніх опорних блоків
        const typeSel = createSelect('process_type', `paragraphs[${index}][process_type]`, card.querySelector('.process-type-container'), master.type);
        const kndkSel = createSelect('kndk_ids', `paragraphs[${index}][kndk_ids][]`, card.querySelector('.kndk-select-container'), master.kndk, true);
        const bossSel = createSelect('position_own_ids', `paragraphs[${index}][position_own_ids][]`, card.querySelector('.boss-select-container'), master.owner, true);
        const divSel = createSelect('division_ids', `paragraphs[${index}][division_ids][]`, card.querySelector('.division-select-container'), master.division, true);
        const executorSel = createSelect('position_ids', `paragraphs[${index}][position_ids][]`, card.querySelector('.executor-select-container'), master.executor, true);
       
        // Вмикаємо локальний пошук для цієї картки
        initCardLiveSearch(card, '.search-kndk', kndkSel);
        initCardLiveSearch(card, '.search-owner', bossSel);
        initCardLiveSearch(card, '.search-executor', executorSel);
        initCardLiveSearch(card, '.search-division', divSel);

        paragraphsContainer.appendChild(card);
    }


    // ==========================================
    // 7. ГОЛОВНИЙ ОБРОБНИК КЛІКУ (ЗАПУСК)
    // ==========================================
    if (parseButton) {
        parseButton.addEventListener('click', function() {
            const text = textInput.value;
            const docId = document.getElementById('doc_id').value;
            const docName = document.getElementById('search_text_hidden').value;
            const doc0id = document.getElementById('doc0_id');
            const doc0name = document.getElementById('doc0_name');
            if (doc0id) doc0id.value = docId;
            if (doc0name) doc0name.value = docName || '';
            if (!text.trim()) return alert('Будь ласка, вставте текст.');
           const paragraphs = text
            .split(/\n\s*\n/)
            .map(p => p.trim())
            .filter(Boolean);
            paragraphsContainer.innerHTML = '';            
            // Отримуємо актуальний стан верхніх опорних блоків
            const masterData = getMasterData();
            // Створюємо картку для кожного абзацу
            /*
            paragraphs[index][title]
            paragraphs[index][keywords]
            paragraphs[index][full_text]
            paragraphs[index][process_type]
            paragraphs[index][kndk_ids][]
            paragraphs[index][division_ids][]
            paragraphs[index][position_own_ids][]
            paragraphs[index][position_ids][]
            */
            console.log('Master Data:', masterData);
            paragraphs.forEach((p, i) => createParagraphCard(p, i, masterData));
            massProcessForm.style.display = 'block';
            massProcessForm.scrollIntoView({ behavior: 'smooth' });
            console.log({parseButton, textInput, massProcessForm, paragraphsContainer});
        });
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
</script>


@endsection
