@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;

    // 1. Шукаємо ВСІ коди у повному тексті до розділення
    // Спрощений та надійний регулярний вираз для форматів X.X або X.X.X (де Х — будь-які цифри)
    preg_match_all('/(?<!\d)\d+\.\d+(?:\.\d+)?(?!\d)/', $item->name, $matches);
    $foundCodes = isset($matches[0]) ? array_unique($matches[0]) : [];

    // 2. Отримуємо моделі з бази даних
    $linkedItems = [];
    if (!empty($foundCodes)) {
        $linkedItems = get_class($item)::whereIn('full_code', $foundCodes)
            ->where('id', '!=', $item->id)
            ->get()
            ->keyBy('full_code');
    }

    // 3. Розбиваємо текст на титул та опис для відображення
    $lines = Str::of($item->name)->explode("\n")->map(fn($line) => trim($line))->filter();
    $title = $lines->first() ?? 'Назва елемента';
    $rawDescription = $lines->skip(1)->implode("\n");

    // 4. Екрануємо опис та замінюємо знайдені коди на HTML-посилання
    $safeDescription = e($rawDescription);
    foreach ($linkedItems as $code => $linkedItem) {
        $route = route('kndks.show', $linkedItem->id);
        $badgeHtml = '<a href="' . $route . '" class="badge bg-primary-subtle text-primary text-decoration-none border border-primary-subtle px-2 py-1 mx-1 fw-bold transition-all">' . e($code) . '</a>';
        
        // Замінюємо код на посилання в описі
        $safeDescription = str_replace($code, $badgeHtml, $safeDescription);
        
        // Якщо потрібно, щоб посилання працювали і в ТИТУЛІ, розкоментуйте рядок нижче:
        // $title = str_replace($code, $badgeHtml, e($title));
    }
@endphp


<div class="container py-5">
    <!-- Кнопка повернення та заголовок -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <!-- ТУТ ВИВОДИТЬСЯ ТІЛЬКИ ПЕРША СТРОКА ЯК ТІТУЛ -->
            <h1 class="h2 text-dark fw-bold mb-0">{{ $title }}</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('kndks.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center bg-white shadow-sm">
                <i class="bi bi-arrow-left me-2"></i> Назад
            </a>
             @if(Auth::user()->hasRole('admin'))      
            <a href="{{ route('kndks.edit',$item) }}" class="btn btn-warning d-inline-flex align-items-center shadow-sm fw-semibold">
                <i class="bi bi-pencil me-2"></i> Редагувати
            </a> @endif
        </div>
    </div>

    <div class="row g-4">
        <!-- Ліва колонка: Основна інформація -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold text-secondary mb-3">Опис</h5>
                    <!-- ТУТ ВИВОДЯТЬСЯ ВСІ ІНШІ СТРОКИ З КЛІКАБЕЛЬНИМИ КОДАМИ -->
                    <div class="card-text text-secondary lh-lg fs-5">
                        {!! nl2br($safeDescription) !!} 
                    </div>
                </div>
            </div>
        </div>

        <!-- Права колонка: Знайдені відповідності кодів -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3 text-primary">
                        <i class="bi bi-link-45deg fs-4 me-2"></i>
                        <h5 class="card-title fw-bold mb-0">Знайдені відповідності</h5>
                    </div>
                    <p class="text-muted small mb-3">Елементи, які згадуються в тексті опису:</p>
                    
                    @if(count($linkedItems) > 0)
                        <div class="d-flex flex-column gap-2">
                            @foreach($linkedItems as $code => $linkedItem)
                                <a href="{{ route('kndks.show', $linkedItem->id) }}" 
                                   class="list-group-item list-group-item-action border rounded-3 p-3 transition-all bg-light bg-opacity-50">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="badge bg-primary px-2.5 py-1.5 fw-bold">{{ $code }}</span>
                                        <i class="bi bi-chevron-right text-muted small"></i>
                                    </div>
                                    <!-- Відображаємо титул знайденого елемента (перший рядок його імені) -->
                                    <small class="text-dark fw-medium d-block text-truncate">
                                        {{ Str::of($linkedItem->name)->explode("\n")->first() }}
                                    </small>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded-3 border border-dashed text-muted small">
                            <i class="bi bi-info-circle d-block fs-3 mb-2 text-secondary"></i>
                            У тексті опису не виявлено активних кодів або збігів
                        </div>
                    @endif
                </div>
            </div>

            <!-- Додаткова системна картка (опціонально) -->
            @if($item->full_code)
            <div class="card border-0 shadow-sm bg-dark text-white">
                <div class="card-body p-4">
                    <span class="text-white-50 d-block small mb-1">Власний код цього елемента:</span>
                    <code class="fs-4 text-warning fw-bold">{{ $item->full_code }}</code>
                </div>
            </div>
            @endif
        </div>
   
        <!-- Права колонка: Знайдені відповідності кодів + Гармошка зв'язків -->
        <div class="col-lg-12">
            


            <!-- 2. НОВА КАРТКА: Гармошка відповідальності (Процеси, Підрозділи, Посади) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3 text-success">
                        <i class="bi bi-diagram-3 fs-4 me-2"></i>
                        <h5 class="card-title fw-bold mb-0">Зв'язки</h5>
                    </div>
                    <div class="accordion accordion-flush" id="kndkRelationsAccordion">                        
                        <!-- Вкладка: Процеси / Функції -->
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="headingProcesses">
                                <button class="accordion-button collapsed fw-semibold px-0 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProcesses" aria-expanded="false" aria-controls="collapseProcesses">
                                    <i class="bi bi-gear me-2 text-muted"></i> 
                                    Процеси / Функції 
                                    <span class="badge bg-light text-dark border ms-2">{{ $item->processes->count() }}</span>
                                </button>
                            </h2>
                            <div id="collapseProcesses" class="accordion-collapse collapse" aria-labelledby="headingProcesses" data-bs-parent="#kndkRelationsAccordion">
                                <div class="accordion-body px-0 py-2">
                                    @if($item->processes->count() > 0)
                                       <ul class="list-group list-group-flush small">
    @foreach($item->processes as $process)
        <li class="list-group-item px-1 border-0 bg-transparent mb-2">
            <!-- Назва процесу діє як інтерактивна кнопка для модалки -->
            <button type="button" 
                    class="btn btn-link p-0 text-start text-decoration-none fw-semibold text-dark shadow-none d-inline-block align-baseline"
                    data-bs-toggle="modal" 
                    data-bs-target="#processModal{{ $process->id }}">
                ⚙️ {{ $process->name }}
            </button>

            <!-- ПРІОРИТЕТ: Виводимо підрозділи самої функції, якщо порожньо — беремо з КНДК -->
            @php 
                $currentDivisions = $process->divisions->isNotEmpty() ? $process->divisions : $item->divisions;
            @endphp

            @if($currentDivisions->isNotEmpty())
                <span class="text-muted fw-normal ms-1 text-secondary">
                    ({{ $currentDivisions->pluck('abv')->implode(', ') }})
                </span>
            @endif

            <!-- Короткий опис-прев'ю -->
            @if($process->description)
                <span class="text-muted d-block small mt-0.5" 
                      data-bs-toggle="modal" 
                      data-bs-target="#processModal{{ $process->id }}"
                      style="cursor: pointer;">
                    {{ Str::limit($process->description, 80) }}
                </span>
            @endif
        </li>

        <!-- Унікальне модальне вікно для кожної функції -->
        <div class="modal fade" id="processModal{{ $process->id }}" tabindex="-1" aria-labelledby="processModalLabel{{ $process->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0 shadow-lg rounded-3">
                    <div class="modal-header bg-light border-bottom-0 py-3">
                        <h5 class="modal-title fw-bold text-dark" id="processModalLabel{{ $process->id }}">
                            📋 Картка процесу / функції
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4 pt-2">
                        <!-- Головні атрибути -->
                        <div class="mb-4">
                            <h4 class="text-primary fw-bold mb-2">{{ $process->name }}</h4>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 rounded-2 text-uppercase font-monospace small">
                                Категорія: {{ $process->type }}
                            </span>
                        </div>

                        <!-- Повний опис -->
                        <div class="mb-4 bg-light p-3 rounded-3 border-start border-primary border-3">
                            <h6 class="fw-bold text-muted mb-2">📄 Детальний опис та регламент</h6>
                            <p class="text-dark mb-0 fs-6" style="white-space: pre-line; line-height: 1.5;">
                                {{ $process->description ?? 'Опис для цієї функції ще не додано.' }}
                            </p>
                        </div>

                        <!-- Секція відповідальних структур -->
                        <div class="row g-3">
                            <!-- Підрозділи функції (або КНДК) -->
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-muted mb-2">🏢 Відповідальні підрозділи</h6>
                                @if($currentDivisions->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($currentDivisions as $div)
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1.5 rounded-2 small" title="{{ $div->name }}">
                                                {{ $div->abv }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small text-italic">Не закріплено</span>
                                @endif
                            </div>

                            <!-- Посади / Власники КНДК -->
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-muted mb-2">👤 Посади (Власники КНДК)</h6>
                                @if($item->responsibles->isNotEmpty())
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($item->responsibles as $pos)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1.5 rounded-2 small">
                                                {{ $pos->abv }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small text-italic">Не закріплено</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0">
                        <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Закрити</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</ul>


                                    @else
                                        <p class="text-muted small my-2 ps-1">Процесів не закріплено</p>
                                    @endif
                                      @if(Auth::user()->hasRole('admin')) 
                                        <a href="{{route('kndks.createprocess')}}?kndk={{ $item->id }}" class="btn btn-primary">
                                            <i class="bi bi-plus-lg"></i> Додати елемент
                                        </a>
                                         <a href="{{route('kndks.massprocess')}}?kndk={{ $item->id }}" class="btn btn-primary">
                                            <i class="bi bi-plus-lg"></i> Додати елементи
                                        </a>
                                        @endif
                                     </div>
                            </div>
                        </div>

                        <!-- Вкладка: Підрозділи -->
                        <div class="accordion-item border-bottom">
                            <h2 class="accordion-header" id="headingDivisions">
                                <button class="accordion-button collapsed fw-semibold px-0 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDivisions" aria-expanded="false" aria-controls="collapseDivisions">
                                    <i class="bi bi-building me-2 text-muted"></i> 
                                    Основні учасники процесу
                                    <span class="badge bg-light text-dark border ms-2">{{ $item->divisions->count() }} - {{ $item->positions->count() }} </span>
                                </button>
                            </h2>
                            <div id="collapseDivisions" class="accordion-collapse collapse" aria-labelledby="headingDivisions" data-bs-parent="#kndkRelationsAccordion">
                                <div class="accordion-body px-0 py-2">
                                    @if($item->divisions->count() > 0)
                                        <div class="d-flex flex-wrap gap-1.5 my-2">
                                            @foreach($item->divisions as $division)
                                                <span class="badge bg-info bg-opacity-10 text-info-emphasis border border-info border-opacity-25 px-2.5 py-1.5 fs-7 rounded-2">
                                                    {{ $division->name }} {{ $division->abv ? "({$division->abv})" : '' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small my-2 ps-1">Підрозділів не закріплено</p>
                                    @endif
                                    @if($item->positions->count() > 0)
                                        <div class="d-flex flex-wrap gap-1.5 my-2">
                                            @foreach($item->positions as $position)
                                                <span class="badge bg-second bg-opacity-10 text-info-emphasis border border-info border-opacity-25 px-2.5 py-1.5 fs-7 rounded-2">
                                                   {{ $position->abv ? "({$position->abv})" : '' }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted small my-2 ps-1">Посад не закріплено</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Вкладка: Посади -->
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingPositions">
                                <button class="accordion-button collapsed fw-semibold px-0 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePositions" aria-expanded="false" aria-controls="collapsePositions">
                                    <i class="bi bi-person-badge me-2 text-muted"></i> 
                                    Власник процесу
                                    <span class="badge bg-light text-dark border ms-2">{{ $item->responsibles->count() }}</span>
                                </button>
                            </h2>
                            <div id="collapsePositions" class="accordion-collapse collapse" aria-labelledby="headingPositions" data-bs-parent="#kndkRelationsAccordion">
                                <div class="accordion-body px-0 py-2">
                                    @if($item->responsibles->count() > 0)
                                        <ul class="list-group list-group-flush small">
                                            @foreach($item->responsibles as $resp)
                                                <li class="list-group-item px-1 border-0 text-dark">
                                                    <i class="bi bi-dot text-secondary"></i> {{ $resp->name }} {{ $resp->abv ? "({$resp->abv})" : '' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted small my-2 ps-1">Посад не закріплено</p>
                                    @endif
                                </div>
                            </div>
                        </div>
<!-- Вкладка: Організації документації (в строку) -->
@php
    $organizations = $item->documents->pluck('organization')->unique()->filter()->values();
@endphp

<div class="accordion-item border-0">
    <h2 class="accordion-header" id="headingOrganizations">
        <button class="accordion-button collapsed fw-semibold px-0 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrganizations" aria-expanded="false" aria-controls="collapseOrganizations">
            <i class="bi bi-building me-2 text-muted"></i> 
            Організації документації
            <span class="badge bg-light text-dark border ms-2">{{ $organizations->count() }}</span>
        </button>
    </h2>
    <div id="collapseOrganizations" class="accordion-collapse collapse" aria-labelledby="headingOrganizations" data-bs-parent="#kndkRelationsAccordion">
        <div class="accordion-body px-1 py-2 text-dark small">
            @if($organizations->count() > 0)
                {{ $organizations->implode(', ') }}
            @else
                <span class="text-muted">Організацій не знайдено</span>
            @endif
        </div>
    </div>
</div>
<!-- НОВИЙ БЛОК: Прив'язані документи з CSV -->
<div class="row g-4 mt-2">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
                    <div class="d-flex align-items-center text-success">
                        <i class="bi bi-file-earmark-text fs-3 me-2"></i>
                        <h4 class="card-title fw-bold mb-0">
                            Прив'язані документи (<span id="jsDocTotalCount">{{ $item->documents->count() }}</span>)
                        </h4>
                    </div>
                     @if(Auth::user()->hasRole('admin'))      
                    <a href="{{ route('kndks.importPage', $item->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm">
                        <i class="bi bi-upload me-2"></i> Завантажити нові CSV
                    </a> @endif
                </div>

                <!-- Панель пошуку всередині документів -->
                <div id="docSearchPanel" class="mb-3 d-none">
                    <div class="input-group input-group-sm" style="max-width: 400px;">
                        <span class="input-group-text bg-white text-muted">🔍</span>
                        <input type="text" id="docSearchInput" class="form-control" placeholder="Пошук за інв. номером, шифром, типом чи організацією...">
                    </div>
                </div>

                <!-- Контейнер для таблиці або placeholder-а -->
                <div id="docTableContainer">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-top">
                            <thead class="table-light text-secondary small text-uppercase">
                                <tr style="cursor: pointer;" id="docTableHeadRow">
                                    <th style="width: 140px;" data-column="inv_no">Інв. Номер <span class="sort-icon"></span></th>
                                    <th data-column="doc_type">Вид документа <span class="sort-icon"></span></th>
                                    <th data-column="code">Шифр / Код <span class="sort-icon"></span></th>
                                    <th data-column="short_content">Тип документа <span class="sort-icon"></span></th>
                                    <th data-column="organization">Організація <span class="sort-icon"></span></th>
                                    <th style="width: 130px;" data-column="is_cancelled">Статус <span class="sort-icon"></span></th>
                                </tr>
                            </thead>
                            <tbody class="text-secondary" id="docTableBody">
                                <!-- Дані згенерує JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Держатель місця (Placeholder), якщо документів немає взагалі -->
                <div id="docEmptyPlaceholder" class="text-center py-5 bg-light rounded-3 border border-dashed text-muted d-none">
                    <i class="bi bi-folder2-open d-block fs-1 mb-2 text-secondary">📁</i>
                    <h5 class="fw-bold text-dark mb-1">Немає завантажених документів</h5>
                    <p class="small mb-3">До цього КНДК ще не прив'язано жодного документа з автоматичного імпорту.</p>
                    <a href="{{ route('kndks.importPage', $item->id) }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i>Імпортувати документи
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    window.documentsRawData = @json($item->documents);
</script>


<style>
    /* Плавні ефекти для кнопок та лінків */
    .transition-all { transition: all 0.2s ease-in-out; }
    .transition-all:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 .25rem .5rem rgba(0,0,0,.05)!important;
        background-color: var(--bs-primary-bg-subtle) !important;
    }
    .list-group-item-action:hover {
        background-color: #fff !important;
        border-color: var(--bs-primary-border-subtle) !important;
    }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const allDocs = window.documentsRawData || [];
    const tableBody = document.getElementById('docTableBody');
    const searchInput = document.getElementById('docSearchInput');
    const searchPanel = document.getElementById('docSearchPanel');
    const emptyPlaceholder = document.getElementById('docEmptyPlaceholder');
    const tableContainer = document.getElementById('docTableContainer');
    const totalCountSpan = document.getElementById('jsDocTotalCount');
    const headRow = document.getElementById('docTableHeadRow');

    // Стан сортування
    let currentSortColumn = '';
    let isAscending = true;
    let filteredDocs = [...allDocs];

    // Якщо документів взагалі немає в базі
    if (allDocs.length === 0) {
        tableContainer.classList.add('d-none');
        emptyPlaceholder.classList.remove('d-none');
        return;
    }

    // Показуємо пошук, якщо є документи
    searchPanel.classList.remove('d-none');

    // Функція рендерингу рядків
    function renderDocs(docs) {
        tableBody.innerHTML = '';
        totalCountSpan.textContent = docs.length;

        if (docs.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        ❌ Нічого не знайдено за вашим запитом серед документів.
                    </td>
                </tr>`;
            return;
        }

        let html = '';
        docs.forEach(doc => {
            const statusBadge = doc.is_cancelled 
                ? '<span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">Анульовано</span>'
                : '<span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Чинний</span>';

            html += `
                <tr>
                    <td>
                    
                        <span class="badge bg-secondary font-monospace px-2 py-1.5 fs-6">${doc.inv_no}</span>                                    
                    </td>
                    <td class="fw-medium text-dark">${doc.doc_type}</td>
                    <td><code class="text-danger fw-bold">${doc.code}</code></td>
                    <td class="small">
                    <a href="/document_show/${doc.inv_no}"  class="text-decoration-none text-dark d-block">
                    ${doc.short_content}
                   </a>
                    </td>
                    <td class="small">
                        <div class="text-truncate" style="max-width: 300px;" title="${doc.short_content}">
                            ${doc.organization}
                        </div>
                    </td>
                    <td>${statusBadge}</td>
                </tr> 
            `;
        });
        tableBody.innerHTML = html;
    }

    // Функція сортування даних
    function sortDocs(column) {
        if (currentSortColumn === column) {
            isAscending = !isAscending; // Міняємо напрямок, якщо клікнули повторно
        } else {
            currentSortColumn = column;
            isAscending = true; // За замовчуванням А-Я
        }

        filteredDocs.sort((a, b) => {
            let valA = a[column];
            let valB = b[column];

            // Для булевого статусу (is_cancelled) сортуємо як 0 та 1
            if (typeof valA === 'boolean') {
                valA = valA ? 1 : 0;
                valB = valB ? 1 : 0;
            } else {
                // Приводимо до нижнього регістру для коректного текстового сортування
                valA = valA.toString().toLowerCase();
                valB = valB.toString().toLowerCase();
            }

            if (valA < valB) return isAscending ? -1 : 1;
            if (valA > valB) return isAscending ? 1 : -1;
            return 0;
        });

        updateSortIcons();
        renderDocs(filteredDocs);
    }

    // Оновлення стрілочок ▲ / ▼ біля стовпців
    function updateSortIcons() {
        const headers = headRow.querySelectorAll('th[data-column]');
        headers.forEach(th => {
            const iconSpan = th.querySelector('.sort-icon');
            const colName = th.getAttribute('data-column');
            
            if (colName === currentSortColumn) {
                iconSpan.textContent = isAscending ? ' ▲' : ' ▼';
                th.classList.add('text-dark', 'fw-bold');
            } else {
                iconSpan.textContent = '';
                th.classList.remove('text-dark', 'fw-bold');
            }
        });
    }

    // Живий пошук
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase().trim();
        
        filteredDocs = allDocs.filter(doc => {
            return doc.inv_no.toLowerCase().includes(query) ||
                   doc.doc_type.toLowerCase().includes(query) ||
                   doc.code.toLowerCase().includes(query) ||
                   doc.short_content.toLowerCase().includes(query) ||
                   doc.organization.toLowerCase().includes(query);
        });

        // Скидаємо сортування під час нового пошуку, щоб не плутати користувача
        currentSortColumn = '';
        updateSortIcons();
        renderDocs(filteredDocs);
    });

    // Вішаємо подію кліку на заголовки таблиці для сортування
    headRow.addEventListener('click', function(e) {
        const th = e.target.closest('th[data-column]');
        if (th) {
            const column = th.getAttribute('data-column');
            sortDocs(column);
        }
    });

    // Ініціалізація: перший вивід документів
    renderDocs(filteredDocs);
});
</script>

@endsection
