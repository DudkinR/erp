@extends('layouts.app')
@section('content')

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Класифікатор СОУ НАЕК 180:2020</h1>
            <p class="text-muted mb-0">Електронний реєстр сфер управління, напрямів та видів діяльності</p>
        </div>
        <a href="{{ route('kndks.search_page') }}" class="btn btn-outline-primary shadow-sm d-inline-flex align-items-center">
            <i class="bi bi-search me-2"></i>
            <span>Розширений пошук КНДК</span>
        </a>
         @if(Auth::user()->hasRole('admin')) 
        <a href="{{route('kndks.create')}}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Додати елемент
        </a>
        @endif
    </div>

    <!-- Нова панель пошуку -->
    <div class="card shadow-sm border-0 rounded-3 mb-3">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted">
                            <i class="bi bi-search">🔍</i>
                        </span>
                        <input type="text" id="kndkSearchInput" class="form-control border-start-0 ps-0" placeholder="Пошук за назвою або цифровим кодом...">
                    </div>
                </div>
                <div class="col-md-4 text-end mt-2 mt-md-0">
                    <span class="text-muted small" id="searchResultCount">Завантаження даних...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Картка з таблицею -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase fs-7 text-muted border-bottom">
                        <tr>
                            <th class="ps-4" style="width: 15%;">Цифровий код</th>
                            <th style="width: 12%;">Рівень</th>
                            <th style="width: 45%;">Найменування</th>
                            <th style="width: 15%;">Об'єкт</th>
                            @if(Auth::user()->hasRole('admin'))   
                            <th class="pe-4 text-end" style="width: 13%;">Дії</th>
                            @endif
                        </tr>
                    </thead>
                    <!-- Сюди скрипт вставлятиме знайдені рядки -->
                    <tbody id="kndkTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ПІДГОТОВКА ДАНИХ ДЛЯ JAVASCRIPT --}}
{{-- Безпечно конвертуємо колекцію Eloquent у масив JSON об'єктів для JS --}}
<script>
    window.kndkRawData = @json($kndks);    
    window.csrfToken = '{{ csrf_token() }}';
</script>

{{-- СКРИПТ ПОШУКУ ТА РЕНДЕРИНГУ --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('kndkSearchInput');
    const tableBody = document.getElementById('kndkTableBody');
    const resultCountSpan = document.getElementById('searchResultCount');
    
    // Отримуємо масив об'єктів, який прийшов з контролера
    const allItems = window.kndkRawData || [];

    // Функція визначення рівня ієрархії на основі наявних полів
    function getItemLevel(item) {
        if (item.level) return parseInt(item.level); // якщо вже передано level
        if (item.group !== null && item.group !== undefined) return 3;
        if (item.subclass !== null && item.subclass !== undefined) return 2;
        return 1; // якщо заповнений тільки class
    }

    // Функція рендерингу рядків таблиці
    function renderTable(items) {
        tableBody.innerHTML = '';
        
        if (items.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        ❌ Нічого не знайдено за вашим запитом.
                    </td>
                </tr>`;
            if (resultCountSpan) resultCountSpan.textContent = 'Знайдено: 0';
            return;
        }

        let htmlRows = ''; 

        items.forEach(item => {
            const level = getItemLevel(item);

            // Класи рядка залежно від рівня
            let trClass = 'text-secondary bg-light bg-opacity-10';
            if (level === 1) trClass = 'fw-bold table-light border-top-2';
            if (level === 2) trClass = 'bg-white';

            // Відступи та бейджі коду
            let padding = '';
            if (level === 2) padding = '&nbsp;&nbsp;&nbsp;';
            if (level === 3) padding = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            let codeBadgeClass = 'bg-light text-dark border';
            if (level === 1) codeBadgeClass = 'bg-dark';
            if (level === 2) codeBadgeClass = 'bg-secondary';

            // Бейдж ієрархії
            let levelBadge = '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2">ІІІ. Група</span>';
            if (level === 1) levelBadge = '<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2">І. Клас</span>';
            if (level === 2) levelBadge = '<span class="badge bg-info-subtle text-info border border-info-subtle px-2">ІІ. Підклас</span>';

            // Обробка переносу рядків у назві
            let nameParts = item.name ? item.name.split('\n') : [''];
            let firstLine = nameParts.shift();
            let remainingText = nameParts.length > 0 ? `<span class="text-muted small d-block">${nameParts.join('<br>')}</span>` : '';
            let fontSizeClass = level === 1 ? 'fs-5' : 'fs-6';

            // Об'єкт класифікації
            let objectBadge = '<span class="text-muted fs-7">—</span>';
            if (item.object_type) {
                let objClass = 'bg-danger';
                if (item.object_type.toLowerCase() === 'документ') objClass = 'bg-success';
                if (item.object_type.toLowerCase() === 'функція') objClass = 'bg-primary';
                
                let ucObject = item.object_type.charAt(0).toUpperCase() + item.object_type.slice(1);
                objectBadge = `<span class="badge text-dark bg-opacity-10 ${objClass}">${ucObject}</span>`;
            }

            // Збираємо HTML одного рядка
            htmlRows += `
                <tr class="${trClass}">
                    <td class="ps-4 font-monospace">
                        ${padding}
                        <span class="badge ${codeBadgeClass}">${item.full_code || ''}</span>
                    </td>
                    <td>${levelBadge}</td>
                    <td><a href="/kndks/${item.id}" class="text-decoration-none text-dark d-block">
                        <span class="${fontSizeClass}">
                            <strong class="fw-bold d-block mb-1">${firstLine}</strong>
                            ${remainingText}
                        </span>
                        </a>
                    </td>
                    <td>${objectBadge}</td>
                         @if(Auth::user()->hasRole('admin'))  
                    <td class="pe-4 text-end">
                        <div class="btn-group btn-group-sm" role="group">
                            <a href="/kndks/${item.id}/edit" class="btn btn-outline-secondary border-0">Редагувати</a>
                            <a href="/kndks/${item.id}/import-page" class="btn btn-outline-secondary border-0">
                                Імпорт докс ${item.documents_count || 0}
                            </a>
                            <form action="/kndks/${item.id}"
                                onsubmit="return confirm('Ви впевнені, що хочете видалити елемент ${item.full_code || ''}?');" 
                                style="display: inline;">
                                <input type="hidden" name="_token" value="${window.csrfToken || ''}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-outline-danger border-0">Видалити</button>
                            </form>
                        </div>
                    </td>
                     @endif
                </tr>
            `;
        });

        tableBody.innerHTML = htmlRows;
        if (resultCountSpan) resultCountSpan.textContent = `Всього: ${items.length}`;
    }

    // Алгоритм лінійного пошуку (залишає ТІЛЬКИ те, що прямо співпадає з кодом або назвою)
    function filterItems(query) {
        if (!query) return allItems; // Якщо рядок пошуку порожній — повертаємо все
        
        query = query.toLowerCase().trim();

        return allItems.filter(item => {
            const nameMatch = item.name && item.name.toLowerCase().includes(query);
            const codeMatch = item.full_code && item.full_code.toLowerCase().includes(query);
            return nameMatch || codeMatch;
        });
    }

    // Слухач події введення тексту
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const filtered = filterItems(e.target.value);
            renderTable(filtered);
        });
    }

    // Перший запуск: відображаємо всі елементи відразу
    renderTable(allItems);
});
</script>

@endsection