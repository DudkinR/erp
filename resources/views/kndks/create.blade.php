@extends('layouts.app')
@section('content')

<div class="container py-4" style="max-width: 800px;">
    <!-- Заголовок -->
    <div class="mb-4">
        <a href="{{ route('kndks.index') }}" class="text-decoration-none text-muted small">
            &larr; Повернутися до списку
        </a>
        <h1 class="h3 mt-2 mb-1">Додати елемент до класифікатора</h1>
        <p class="text-muted">СОУ НАЕК 180:2020 (Введення в електронну форму)</p>
    </div>

    <!-- Картка форми -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{  route('kndks.store')  }}" method="POST" id="kndkCreateForm">
                @csrf

                <!-- Рівень ієрархії (Визначає логіку заповнення) -->
                <div class="mb-4 p-3 bg-light rounded-3 border">
                    <label class="form-label fw-bold text-dark d-block">Рівень ієрархії, що створюється:</label>
                    <div class="form-check form-check-inline mt-1">
                        <input class="form-check-input" type="radio" name="level_toggle" id="level1" value="1" checked>
                        <label class="form-check-input-label fw-semibold text-primary" for="level1">I. Клас (Сфера)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="level_toggle" id="level2" value="2">
                        <label class="form-check-input-label fw-semibold text-info" for="level2">II. Підклас (Напрям)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="level_toggle" id="level3" value="3">
                        <label class="form-check-input-label fw-semibold text-warning" for="level3">III. Група (Вид робіт)</label>
                    </div>
                </div>

                <div class="row g-3">
                    <!-- Поле 1: Клас -->
                    <div class="col-md-4" id="class_box">
                        <label for="class" class="form-label fw-semibold">Клас (1 цифра) <span class="text-danger">*</span></label>
                        <input type="number" name="class" id="class" class="form-control @error('class') is-invalid @enderror" 
                               value="{{ old('class') }}" placeholder="Напр: 1" min="1" max="9" required>
                        @error('class') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Поле 2: Підклас -->
                    <div class="col-md-4 d-none" id="subclass_box">
                        <label for="subclass" class="form-label fw-semibold">Підклас (2 цифри) <span class="text-danger">*</span></label>
                        <input type="text" name="subclass" id="subclass" class="form-control @error('subclass') is-invalid @enderror" 
                               value="{{ old('subclass') }}" placeholder="Напр: 40" maxlength="2" pattern="[0-9]{2}">
                        @error('subclass') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Поле 3: Група -->
                    <div class="col-md-4 d-none" id="group_box">
                        <label for="group" class="form-label fw-semibold">Група (2 цифри) <span class="text-danger">*</span></label>
                        <input type="text" name="group" id="group" class="form-control @error('group') is-invalid @enderror" 
                               value="{{ old('group') }}" placeholder="Напр: 01"  maxlength="5" pattern="[0-9\-]{2,5}">
                        @error('group') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <!-- Візуальне прев'ю фінального коду -->
                    <div class="col-12">
                        <div class="p-2 px-3 bg-dark text-white rounded font-monospace fs-6 d-inline-block">
                            Цифровий код: <span id="code_preview" class="text-warning fw-bold">1</span>
                        </div>
                        <!-- Приховане поле для відправки full_code в базу -->
                        <input type="hidden" name="full_code" id="full_code" value="1">
                    </div>

                 <!-- Найменування -->
                    <div class="col-12 mt-3">
                        <label for="name" class="form-label fw-semibold">Найменування <span class="text-danger">*</span></label>
                        
                        <textarea name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                rows="3" placeholder="Введіть назву сфери, напряму або детального виду діяльності (робіт)..." required>{{ old('name') }}</textarea>
                                
                        @error('name') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>


                    <!-- Об'єкт класифікації (Пункт 5.1.2) -->
                    <div class="col-12" id="object_type_box">
                        <label for="object_type" class="form-label fw-semibold">Об'єкт класифікації</label>
                        <select name="object_type" id="object_type" class="form-select @error('object_type') is-invalid @enderror">
                            <option value="" selected>— Не вказано (не застосовується) —</option>
                            <option value="документ" {{ old('object_type') == 'документ' ? 'selected' : '' }}>Документ</option>
                            <option value="функція" {{ old('object_type') == 'функція' ? 'selected' : '' }}>Функція управління</option>
                            <option value="захід" {{ old('object_type') == 'захід' ? 'selected' : '' }}>Захід</option>
                        </select>
                        @error('object_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Кнопки дій -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('kndks.index') }}" class="btn btn-light px-4">Скасувати</a>
                    <button type="submit" class="btn btn-primary px-4">Зберегти запис</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Скрипт інтерактивності форми -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const levelToggles = document.querySelectorAll('input[name="level_toggle"]');
    const classBox = document.getElementById('class_box');
    const subclassBox = document.getElementById('subclass_box');
    const groupBox = document.getElementById('group_box');

    const classInput = document.getElementById('class');
    const subclassInput = document.getElementById('subclass');
    const groupInput = document.getElementById('group');
    
    const codePreview = document.getElementById('code_preview');
    const fullCodeHidden = document.getElementById('full_code');

    // Перемикання рівнів (динамічно ховаємо/показуємо поля та ставимо 'required')
    levelToggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const level = this.value;

            if (level === "1") {
                subclassBox.classList.add('d-none');
                groupBox.classList.add('d-none');
                subclassInput.required = false;
                groupInput.required = false;
                subclassInput.value = '';
                groupInput.value = '';
            } else if (level === "2") {
                subclassBox.classList.remove('d-none');
                groupBox.classList.add('d-none');
                subclassInput.required = true;
                groupInput.required = false;
                groupInput.value = '';
            } else if (level === "3") {
                subclassBox.classList.remove('d-none');
                groupBox.classList.remove('d-none');
                subclassInput.required = true;
                groupInput.required = true;
            }
            updateCodePreview();
        });
    });

    // Живе прев'ю згенерованого коду (Формат X.XX.XX)
    function updateCodePreview() {
        const c = classInput.value || 'X';
        const sc = subclassInput.value || 'XX';
        const g = groupInput.value || 'XX';
        
        const activeLevel = document.querySelector('input[name="level_toggle"]:checked').value;
        let finalCode = '';

        if (activeLevel === "1") {
            finalCode = c;
        } else if (activeLevel === "2") {
            finalCode = `${c}.${sc}`;
        } else {
            finalCode = `${c}.${sc}.${g}`;
        }

        codePreview.textContent = finalCode;
        fullCodeHidden.value = finalCode; // Записуємо значення в hidden input для бекенду
    }

    // Слухаємо введення даних
    classInput.addEventListener('input', updateCodePreview);
    subclassInput.addEventListener('input', updateCodePreview);
    groupInput.addEventListener('input', updateCodePreview);
});
</script>

@endsection