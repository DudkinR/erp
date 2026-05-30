@extends('layouts.app')
@section('content')

<div class="container py-4" style="max-width: 900px;">
    <!-- Сповіщення про успішне збереження/оновлення -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
            <form action="{{ route('kndks.store') }}" method="POST" id="processCreateForm">
                @csrf
                
                    <h5 class="text-primary mb-3 border-bottom pb-2">📂 Основна інформація про процес</h5>
                    <div class="row g-3 mb-4">
                        <!-- Ліва колонка: Назва процесу -->
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">
                                Назва процесу <span id="name_required_star" class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" placeholder="Введіть назву нового процесу або залиште порожнім">
                            <div class="form-text text-muted small">Якщо залишити порожнім, новий процес не створиться, а прив'язка піде прямо до КНДК.</div>
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
                            </select>
                            <div class="form-text text-muted small">Оберіть категорію, до якої належить цей елемент процесу.</div>
                            @error('process_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Нижня колонка: Опис процесу -->
                        <div class="col-12">
                            <label for="description" class="form-label fw-semibold">Опис процесу</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                    rows="3" placeholder="Детальний опис кроків чи регламенту процесу...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <h5 class="text-primary mb-3 border-bottom pb-2">🔗 Налаштування зв'язків</h5>
                    <div class="row g-3">
                        <!-- Зв'язок з КНДК (Багато до багато) -->
                        <div class="col-12">
                            <label for="kndk_ids" class="form-label fw-semibold">Пов'язані елементи КНДК (СОУ НАЕК 180:2020) <span class="text-danger">*</span></label>
                            <select name="kndk_ids[]" id="kndk_ids" class="form-select @error('kndk_ids') is-invalid @enderror" multiple required style="min-height: 150px;">
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
                                    
                                    <label for="position_own_ids" class="form-label fw-semibold">Відповідальні посади (власники)</label>
                                    <select name="position_own_ids[]" id="position_own_ids" class="form-select @error('position_own_ids') is-invalid @enderror" multiple style="min-height: 280px;">
                                        @foreach($positions as $position)
                                            <option value="{{ $position->id }}" {{ (is_array(old('position_own_ids')) && in_array($position->id, old('position_own_ids'))) ? 'selected' : '' }}>
                                                {{ $position->abv }}
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
                                        <label for="division_ids" class="form-label fw-semibold">Відповідальні підрозділи</label>
                                        <select name="division_ids[]" id="division_ids" class="form-select @error('division_ids') is-invalid @enderror" multiple style="min-height: 100px;">
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
                                        <label for="position_ids" class="form-label fw-semibold">Відповідальні посади</label>
                                        <select name="position_ids[]" id="position_ids" class="form-select @error('position_ids') is-invalid @enderror" multiple style="min-height: 100px;">
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" {{ (is_array(old('position_ids')) && in_array($position->id, old('position_ids'))) ? 'selected' : '' }}>
                                                    {{ $position->abv }}
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


                <!-- Кнопки дій -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('kndks.index') }}" class="btn btn-light px-4">Скасувати</a>
                    <button type="submit" id="submitBtn" class="btn btn-success px-4">Зберегти зв'язки</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript секція для інтерактивності --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('name');
    const requiredStar = document.getElementById('name_required_star');
    const submitBtn = document.getElementById('submitBtn');

    function toggleFormBehavior() {
        if (nameInput.value.trim() !== '') {
            // Сценарій: Користувач пише назву процесу
            requiredStar.style.display = 'inline';
            submitBtn.textContent = 'Створити процес та підв\'язати';
            submitBtn.className = 'btn btn-success px-4';
        } else {
            // Сценарій: Назва процесу порожня (тільки зв'язування КНДК з підрозділами/посадами)
            requiredStar.style.display = 'none';
            submitBtn.textContent = 'Тільки пов\'язати з КНДК';
            submitBtn.className = 'btn btn-primary px-4';
        }
    }

    // Слухаємо події введення тексту
    nameInput.addEventListener('input', toggleFormBehavior);
    
    // Ініціалізуємо стан при завантаженні (на випадок повернення форми із old() даними)
    toggleFormBehavior();
});
</script>

@endsection
