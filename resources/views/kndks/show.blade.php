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
            <a href="{{ route('kndks.edit',$item) }}" class="btn btn-warning d-inline-flex align-items-center shadow-sm fw-semibold">
                <i class="bi bi-pencil me-2"></i> Редагувати
            </a>
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
    </div>
    <!-- НОВИЙ БЛОК: Прив'язані документи з CSV -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-3">
                        <div class="d-flex align-items-center text-success">
                            <i class="bi bi-file-earmark-text fs-3 me-2"></i>
                            <h4 class="card-title fw-bold mb-0">Прив'язані документи ({{ $item->documents->count() }})</h4>
                        </div>
                        <!-- Кнопка для швидкого переходу на сторінку імпорту нових документів -->
                        <a href="{{ route('kndks.importPage', $item->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center shadow-sm">
                            <i class="bi bi-upload me-2"></i> Завантажити нові CSV
                        </a>
                    </div>

                    @if($item->documents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle border-top">
                                <thead class="table-light text-secondary small text-uppercase">
                                    <tr>
                                        <th style="width: 120px;">Інв. Номер</th>
                                        <th>Вид документа</th>
                                        <th>Шифр / Код</th>
                                        <th>Тип документа</th>
                                        <th>Організація</th>
                                        <th style="width: 100px;">Статус</th>
                                    </tr>
                                </thead>
                                <tbody class="text-secondary">
                                    @foreach($item->documents as $doc)
                                        <tr>
                                            <!-- Первинний ключ-строка (Inv. No) -->
                                            <td>
                                                 <span class="badge bg-secondary font-monospace px-2 py-1.5 fs-6">{{ $doc->inv_no }}</span>                                    
                                            </td>
                                            <td class="fw-medium text-dark">{{ $doc->doc_type ?? '-' }}</td>
                                            <td>                                              
                                            <code class="text-danger fw-bold">{{ $doc->code  ?? '-' }}</code>
                                            </td>
                                            <td class="small">{{ $doc->short_content ?? '-' }}</td>
                                            <td class="small">
                                                <div class="text-truncate" style="max-width: 300px;" title="{{ $doc->short_content }}">
                                                    {{ $doc->organization ?? '-' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if($doc->is_cancelled)
                                                    <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">Анульовано</span>
                                                @else
                                                    <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Діючий</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <!-- Держатель місця (Placeholder), якщо документів немає -->
                        <div class="text-center py-5 bg-light rounded-3 border border-dashed text-muted">
                            <i class="bi bi-folder2-open d-block fs-1 mb-2 text-secondary"></i>
                            <h5 class="fw-bold text-dark mb-1">Немає завантажених документів</h5>
                            <p class="small mb-3">До цього КНДК ще не прив'язано жодного документа з автоматичного імпорту.</p>
                            <a href="{{ route('kndks.importPage', $item->id) }}" class="btn btn-primary btn-sm px-4 shadow-sm">
                                <i class="bi bi-plus-lg me-2"></i>Імпортувати документи
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

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
@endsection
