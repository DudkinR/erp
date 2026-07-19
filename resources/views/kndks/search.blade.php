@extends('layouts.app')
@section('content')
<div class="container mt-5" style="max-width: 700px;">
    <!-- CSRF-токен для безпечних POST-запитів -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h4 class="mb-4 fw-bold text-primary"><i class="bi bi-search me-2"></i> Пошук КНДК</h4>
            
            <!-- ВИПРАВЛЕНО: Додано метод POST та повне блокування стандартного надсилання форми через event.preventDefault() -->
            <form id="kndkSearchForm" method="POST" action="{{ route('kndks.search') }}" onsubmit="event.preventDefault();">
                <!-- Велика текстова область -->
                <div class="mb-3 position-relative">
                    <label for="kndkSearchInput" class="form-label text-muted small fw-semibold">Введіть або вставте текст для пошуку</label>
                    <textarea id="kndkSearchInput" class="form-control form-control-lg p-3" rows="4" placeholder="Введіть коди, назви, посади або підрозділи (можна в кілька рядків)..." style="resize: vertical;"></textarea>
                    
                    <!-- Абсолютна кнопка очищення всередині textarea -->
                    <button class="btn btn-sm btn-light border position-absolute d-none" type="button" id="clearSearchBtn" style="top: 40px; right: 15px; z-index: 5;">
                        <i class="bi bi-x-lg"></i> Очистити
                    </button>
                </div>

                <!-- Кнопка відправки форми -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted small">
                        <kbd>Ctrl</kbd> + <kbd>Enter</kbd> для швидкого пошуку
                    </div>
                    <button class="btn btn-primary btn-lg px-5 shadow-sm" type="button" id="submitSearchBtn">
                        <span id="btnLoader" class="spinner-border spinner-border-sm me-2 d-none" role="status" aria-hidden="true"></span>
                        <i id="btnIcon" class="bi bi-search me-1"></i> Знайти КНДК
                    </button>
                </div>
            </form>

            <!-- Блок результатів -->
            <div id="searchResults" class="list-group list-group-flush mt-4 border rounded d-none" style="max-height: 400px; overflow-y: auto;">
                <!-- Сюди JS підставить результати -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('kndkSearchInput');
    const searchResults = document.getElementById('searchResults');
    const submitBtn = document.getElementById('submitSearchBtn');
    const clearBtn = document.getElementById('clearSearchBtn');
    const btnLoader = document.getElementById('btnLoader');
    const btnIcon = document.getElementById('btnIcon');
    const searchRouteUrl = "{{ route('kndks.search') }}"; 
    const viewBaseUrl = "{{ url('/kndks') }}";    
    const urlPrefixes = {
        kndks: "{{ url('kndks') }}",
        processes: "{{ url('processes') }}",
        documents: "{{ url('documents') }}",
        positions: "{{ url('positions') }}",
        divisions: "{{ url('divisions') }}"
    };
    function toggleClearButton() {
        clearBtn.classList.toggle('d-none', searchInput.value.trim().length === 0);
    }

    toggleClearButton();

    function handleSearch() {
        const query = searchInput.value.trim();
        
        if (query.length < 2) {
            searchResults.innerHTML = `<div class="list-group-item p-3 text-warning text-center small">Будь ласка, введіть хоча б 2 символи для пошуку</div>`;
            searchResults.classList.remove('d-none');
            return;
        }

        fetchResults(query);
    }

    submitBtn.addEventListener('click', handleSearch);

    // ВИПРАВЛЕНО: Коректна обробка клавіш у textarea.
    // Звичайний Enter робить перенос рядка, а Ctrl+Enter або Cmd+Enter запускає пошук без перезавантаження сторінки.
    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault(); // Зупиняємо перенос рядка та надсилання форми
                handleSearch();     // Запускаємо AJAX
            }
        }
    });

    searchInput.addEventListener('input', toggleClearButton);

    async function fetchResults(query) {
        submitBtn.disabled = true;
        btnLoader.classList.remove('d-none');
        btnIcon.classList.add('d-none');
        
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
        
        try {
            const response = await fetch(searchRouteUrl, {
                method: 'POST',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ query: query })
            });
            
            if (!response.ok) {
                throw new Error(`Сервер повернув код стану: ${response.status}`);
            }

            const data = await response.json();
            renderResults(data);
            
            // Показуємо кнопку очищення, оскільки в полі є текст і є результати
            if (clearBtn && query.length > 0) {
                clearBtn.classList.remove('d-none');
            }
        } catch (error) {
            console.error('Помилка пошуку:', error);
            searchResults.innerHTML = `<div class="list-group-item p-3 text-danger text-center small">Сталася помилка під час виконання запиту</div>`;
            searchResults.classList.remove('d-none');
        } finally {
            submitBtn.disabled = false;
            btnLoader.classList.add('d-none');
            btnIcon.classList.remove('d-none');
        }
    }
    function renderResults(data) {
        searchResults.innerHTML = '';
        
        // Перевірка: якщо прийшла пуста відповідь або не об'єкт
        if (!data || typeof data !== 'object') {
            searchResults.innerHTML = `<div class="list-group-item p-3 text-danger text-center small">Помилка обробки результатів</div>`;
            searchResults.classList.remove('d-none');
            return;
        }

        // Конфігурація відображення блоків: назва, масив даних, префікс посилання та генератор контенту
        const categories = [
            {
                title: 'Класифікатор КНДК',
                items: data.kndks || [],
                baseUrl: urlPrefixes.kndks,
                renderRow: (item) => `
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-2 font-monospace">${item.full_code || ''}</span>
                        <span class="text-dark fw-medium">${item.name || ''}</span>
                    </div>`
            },
            {
                title: 'Технологічні процеси',
                items: data.processes || [],
                baseUrl: urlPrefixes.processes,
                renderRow: (item) => `
                    <div>
                        <span class="text-dark fw-medium">${item.name || ''}</span>
                        ${item.description ? `<div class="text-muted small mt-1 text-truncate" style="max-width: 500px;">${item.description}</div>` : ''}
                    </div>`
            },
            {
                title: 'Пов\'язані документи',
                items: data.documents || [],
                baseUrl: urlPrefixes.documents,
                renderRow: (item) => `
                    <div>
                        <span class="text-dark fw-medium text-wrap">${item.short_content || 'Документ без назви'}</span>
                        ${item.organization ? `<div class="text-muted small mt-1 font-monospace">${item.organization}</div>` : ''}
                    </div>`
            },
            {
                title: 'Посади та виконавці',
                items: data.positions || [],
                baseUrl: urlPrefixes.positions,
                renderRow: (item) => `
                    <div>
                        ${item.abv ? `<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle me-2">${item.abv}</span>` : ''}
                        <span class="text-dark fw-medium">${item.name || ''}</span>
                    </div>`
            },
            {
                title: 'Структурні підрозділи (Цехи)',
                items: data.divisions || [],
                baseUrl: urlPrefixes.divisions,
                renderRow: (item) => `
                    <div>
                        ${item.abv ? `<span class="badge bg-info-subtle text-info border border-info-subtle me-2">${item.abv}</span>` : ''}
                        <span class="text-dark fw-medium">${item.name || ''}</span>
                    </div>`
            }
        ];

        let totalFound = 0;

        // Проходимо по кожній категорії та рендеримо її блок, якщо є результати
        categories.forEach(category => {
            if (category.items.length === 0) return;

            totalFound += category.items.length;

         
            // Шаблон заголовка блоку (категорії) з покращеним візуальним виділенням
            const categoryHeader = `
                <div class="list-group-item bg-body-secondary text-dark fw-bold small text-uppercase py-3 px-3 border-start border-primary border-4 shadow-sm mt-3 mb-1 d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-folder2-open me-2 text-primary"></i>${category.title}
                    </span>
                    <span class="badge bg-primary text-white rounded-pill px-2.5 py-1">${category.items.length} результатів</span>
                </div>
            `;
            searchResults.insertAdjacentHTML('beforeend', categoryHeader);


            // Рендеримо рядки для поточного блоку
            category.items.forEach(item => {
                // Якщо це категорія документів, використовуємо інвентарний номер замість id
                let targetId = item.id;
                if (category.baseUrl === urlPrefixes.documents) {
                    baseurl='document_show';
                    targetId = item.inv_no;
                }
                else{
                    baseurl=category.baseUrl;
                }

                const htmlRow = `
                    <a href="${baseurl}/${targetId}" class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center border-start-0 border-end-0">
                        ${category.renderRow(item)}
                        <i class="bi bi-chevron-right text-muted small"></i>
                    </a>
                `;
                searchResults.insertAdjacentHTML('beforeend', htmlRow);
            });

        });

        // Якщо взагалі ні в одному масиві нічого немає
        if (totalFound === 0) {
            searchResults.innerHTML = `<div class="list-group-item p-3 text-muted text-center small">Нічого не знайдено за цим запитом</div>`;
        }

        searchResults.classList.remove('d-none');
    }

    clearBtn.addEventListener('click', function () {
        searchInput.value = '';
        searchResults.innerHTML = '';
        searchResults.classList.add('d-none');
        this.classList.add('d-none');
        searchInput.focus();
    });

});
</script>

@endsection
