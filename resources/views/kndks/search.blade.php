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

    const viewBaseUrl = "{{ url('/kndks') }}";

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
            // Використовуємо POST запит замість GET
            const response = await fetch(`{{ route('kndks.search') }}`, {
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

    function renderResults(items) {
        searchResults.innerHTML = '';
        
        if (items.length === 0) {
            searchResults.innerHTML = `<div class="list-group-item p-3 text-muted text-center small">Нічого не знайдено за цим запитом</div>`;
            searchResults.classList.remove('d-none');
            return;
        }

        items.forEach(item => {
            const html = `
                <a href="${viewBaseUrl}/${item.id}" class="list-group-item list-group-item-action p-3 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-2 font-monospace">${item.full_code}</span>
                        <span class="text-dark fw-medium">${item.name || 'Процес КНДК'}</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </a>
            `;
            searchResults.insertAdjacentHTML('beforeend', html);
        });

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
