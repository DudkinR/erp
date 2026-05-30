@extends('layouts.app')
@section('content')
<div class="container py-4" style="max-width: 800px;">
    <!-- Заголовок -->
    <div class="mb-4">
        <a href="{{ route('kndks.index') }}" class="text-decoration-none text-muted small">
            &larr; Повернутися до списку
        </a>
        <h1 class="h3 mt-2 mb-1">Редагувати елемент класифікатора</h1>
        <p class="text-muted">Поточний код в базі: <strong class="text-dark">{{ $kndk->full_code }}</strong></p>
    </div>

    <!-- Картка форми -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('kndks.update', $kndk->id) }}" method="POST" id="kndkEditForm">
                @csrf
                @method('PUT')

                <!-- Рівень ієрархії (Лише для читання/інформації, щоб не поламати структуру зв'язків) -->
                <div class="mb-4 p-3 bg-light rounded-3 border">
                    <label class="form-label fw-bold text-dark d-block">Рівень ієрархії елемента:</label>
                    <div class="fw-semibold">
                        @if($kndk->level === 1)
                            <span class="badge bg-primary px-3 py-2 fs-7">I. Клас (Сфера управління)</span>
                        @elseif($kndk->level === 2)
                            <span class="badge bg-info text-dark px-3 py-2 fs-7">II. Підклас (Напрям діяльності)</span>
                        @else
                            <span class="badge bg-warning text-dark px-3 py-2 fs-7">III. Група (Детальний вид діяльності)</span>
                        @endif
                    </div>
                    <!-- Приховане поле для JS логіки -->
                    <input type="hidden" id="active_level" value="{{ $kndk->level }}">
                </div>

                <div class="row g-3">
                    <!-- Поле 1: Клас -->
                    <div class="col-md-4" id="class_box">
                        <label for="class" class="form-label fw-semibold">Клас (1 цифра) <span class="text-danger">*</span></label>
                        <input type="number" name="class" id="class" class="form-control @error('class') is-invalid @enderror" 
                               value="{{ old('class', $kndk->class) }}" placeholder="Напр: 1" min="1" max="9" required>
                        @error('class') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Поле 2: Підклас -->
                    <div class="col-md-4 {{ $kndk->level < 2 ? 'd-none' : '' }}" id="subclass_box">
                        <label for="subclass" class="form-label fw-semibold">Підклас (2 цифри) <span class="text-danger">*</span></label>
                        <input type="text" name="subclass" id="subclass" class="form-control @error('subclass') is-invalid @enderror" 
                               value="{{ old('subclass', $kndk->subclass) }}" placeholder="Напр: 40" maxlength="2" pattern="[0-9]{2}"
                               {{ $kndk->level >= 2 ? 'required' : '' }}>
                        @error('subclass') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Поле 3: Група -->
                    <div class="col-md-4 {{ $kndk->level < 3 ? 'd-none' : '' }}" id="group_box">
                        <label for="group" class="form-label fw-semibold">Група (2 цифри) <span class="text-danger">*</span></label>
                        <input type="text" name="group" id="group" class="form-control @error('group') is-invalid @enderror" 
                               value="{{ old('group', $kndk->group) }}" placeholder="Напр: 01" maxlength="5" pattern="[0-9,'-']{5}"
                               {{ $kndk->level === 3 ? 'required' : '' }}>
                        @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Візуальне прев'ю оновленого коду -->
                    <div class="col-12">
                        <div class="p-2 px-3 bg-dark text-white rounded font-monospace fs-6 d-inline-block">
                            Новий цифровий код: <span id="code_preview" class="text-warning fw-bold">{{ $kndk->full_code }}</span>
                        </div>
                        <!-- Приховане поле для відправки full_code в базу -->
                        <input type="hidden" name="full_code" id="full_code" value="{{ $kndk->full_code }}">
                    </div>

                    <!-- Найменування з першим рядком (Textarea) -->
                    <div class="col-12 mt-3">
                        <label for="name" class="form-label fw-semibold">Найменування <span class="text-danger">*</span></label>
                        <textarea name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                  rows="4" placeholder="Введіть назву сфери, напряму або детального виду діяльності (робіт)..." required>{{ old('name', $kndk->name) }}</textarea>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text text-muted">Перший рядок автоматично виділятиметься жирним шрифтом у таблиці.</div>
                    </div>

                    <!-- Об'єкт класифікації (Пункт 5.1.2) -->
                    <div class="col-12" id="object_type_box">
                        <label for="object_type" class="form-label fw-semibold">Об'єкт класифікації</label>
                        <select name="object_type" id="object_type" class="form-select @error('object_type') is-invalid @enderror">
                            <option value="">— Не вказано (не застосовується) —</option>
                            <option value="документ" {{ old('object_type', $kndk->object_type) == 'документ' ? 'selected' : '' }}>Документ</option>
                            <option value="функція" {{ old('object_type', $kndk->object_type) == 'функція' ? 'selected' : '' }}>Функція управління</option>
                            <option value="захід" {{ old('object_type', $kndk->object_type) == 'захід' ? 'selected' : '' }}>Захід</option>
                        </select>
                        @error('object_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
              <h5 class="text-primary mb-3 border-bottom pb-2">🔗 Налаштування зв'язків</h5>
<div class="row g-3">

    <!-- Ліва колонка: Власник процесу -->
    <div class="col-md-6">
        <div class="card h-100 bg-light-subtle">
            <div class="card-body">
                <h6 class="card-title fw-bold text-secondary mb-3">Власник процесу</h6>
                
                <label for="position_own_ids" class="form-label fw-semibold">Відповідальні посади (власники)</label>
                <select name="position_own_ids[]" id="position_own_ids" class="form-select @error('position_own_ids') is-invalid @enderror" multiple style="min-height: 280px;">
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" 
                            {{ 
                                (is_array(old('position_own_ids')) && in_array($position->id, old('position_own_ids'))) || 
                                (isset($kndk) && $kndk->responsibles->contains($position->id)) 
                                ? 'selected' : '' 
                            }}>
                            {{ $position->abv }}
                        </option>
                    @endforeach
                </select>
                <div class="form-text text-muted small">Утримуйте <kbd>Ctrl</kbd> або <kbd>Cmd</kbd> для viбору кількох.</div>
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
                            <option value="{{ $division->id }}" 
                                {{ 
                                    (is_array(old('division_ids')) && in_array($division->id, old('division_ids'))) || 
                                    (isset($kndk) && $kndk->divisions->contains($division->id)) 
                                    ? 'selected' : '' 
                                }}>
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
                            <option value="{{ $position->id }}" 
                                {{ 
                                    (is_array(old('position_ids')) && in_array($position->id, old('position_ids'))) || 
                                    (isset($kndk) && $kndk->positions->contains($position->id)) 
                                    ? 'selected' : '' 
                                }}>
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
                    <button type="submit" class="btn btn-success px-4">Оновити запис</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Скрипт динамічного формування full_code -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const activeLevel = parseInt(document.getElementById('active_level').value);
    
    const classInput = document.getElementById('class');
    const subclassInput = document.getElementById('subclass');
    const groupInput = document.getElementById('group');
    
    const codePreview = document.getElementById('code_preview');
    const fullCodeHidden = document.getElementById('full_code');

    function updateCodePreview() {
        const c = classInput.value || 'X';
        const sc = subclassInput.value || 'XX';
        const g = groupInput.value || 'XX';
        
        let finalCode = '';

        if (activeLevel === 1) {
            finalCode = c;
        } else if (activeLevel === 2) {
            finalCode = `${c}.${sc}`;
        } else if (activeLevel === 3) {
            finalCode = `${c}.${sc}.${g}`;
        }

        codePreview.textContent = finalCode;
        fullCodeHidden.value = finalCode;
    }

    // Слухаємо зміни в інпутах
    classInput.addEventListener('input', updateCodePreview);
    if (subclassInput) subclassInput.addEventListener('input', updateCodePreview);
    if (groupInput) groupInput.addEventListener('input', updateCodePreview);
});
</script>
@endsection
