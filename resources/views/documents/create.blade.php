@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 d-flex align-items-center">
                        <i class="bi bi-file-earmark-plus me-2"></i> Створення нового документа
                    </h5>
                </div>
                <div class="card-body p-4 bg-light">
                    <form action="{{ route('documents.store') }}" method="POST">
                        @csrf

                        <!-- Секція 1: Основна інформація -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="text-primary border-bottom pb-2 mb-3">Основна інформація</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="inv_no" class="form-label font-weight-bold">Інвентарний № *</label>
                                        <input type="text" name="inv_no" id="inv_no" class="form-control @error('inv_no') is-invalid @enderror" value="{{ old('inv_no') }}" required>
                                        @error('inv_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="doc_type" class="form-label">Тип документа</label>
                                        <input type="text" name="doc_type" id="doc_type" class="form-control @error('doc_type') is-invalid @enderror" value="{{ old('doc_type') }}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="code" class="form-label">Код</label>
                                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="organization" class="form-label">Організація</label>
                                        <input type="text" name="organization" id="organization" class="form-control @error('organization') is-invalid @enderror" value="{{ old('organization') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="project" class="form-label">Проєкт</label>
                                        <input type="text" name="project" id="project" class="form-control @error('project') is-invalid @enderror" value="{{ old('project') }}">
                                    </div>
                                    <div class="col-12">
                                        <label for="short_content" class="form-label">Короткий зміст</label>
                                        <textarea name="short_content" id="short_content" rows="3" class="form-control @error('short_content') is-invalid @enderror">{{ old('short_content') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Секція 2: Дати та Життєвий цикл -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="text-primary border-bottom pb-2 mb-3">Терміни та Життєвий цикл</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label for="date_reg" class="form-label">Дата реєстрації</label>
                                        <input type="date" name="date_reg" id="date_reg" class="form-control" value="{{ old('date_reg') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_start" class="form-label">Дата початку дії</label>
                                        <input type="date" name="date_start" id="date_start" class="form-control" value="{{ old('date_start') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="date_end" class="form-label">Дата закінчення</label>
                                        <input type="date" name="date_end" id="date_end" class="form-control" value="{{ old('date_end') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="registration_date" class="form-label">Дата держ. реєстрації</label>
                                        <input type="date" name="registration_date" id="registration_date" class="form-control" value="{{ old('registration_date') }}">
                                    </div>
                                    
                                    <div class="col-md-4 border-end">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_cancelled" id="is_cancelled" value="1" {{ old('is_cancelled') ? 'checked' : '' }} onchange="document.getElementById('cancel_date_block').classList.toggle('d-none', !this.checked)">
                                            <label class="form-check-label" for="is_cancelled">Скасовано</label>
                                        </div>
                                        <div class="mt-2 d-none" id="cancel_date_block">
                                            <label for="cancellation_date" class="form-label small">Дата скасування</label>
                                            <input type="date" name="cancellation_date" id="cancellation_date" class="form-control form-control-sm" value="{{ old('cancellation_date') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-check form-switch mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_reissued" id="is_reissued" value="1" {{ old('is_reissued') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_reissued">Перевидано</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                            <!-- Секція 3: Налаштування зв'язків -->
                    <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">🔗 Налаштування зв'язків</h5>
                    <div class="row g-3">
                        <!-- Зв'язок з КНДК (Багато до багато) -->
                        <div class="col-12">
                            <label for="kndk_ids" class="form-label fw-semibold">
                                Пов'язані елементи КНДК (СОУ НАЕК 180:2020)
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group mb-2">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
                                <input
                                    type="text"
                                    id="search_kndk"
                                    class="form-control"
                                    placeholder="Почніть вводити код або назву для фільтрації списку..."
                                >
                            </div>

                            <select
                                name="kndk_ids[]"
                                id="kndk_ids"
                                class="form-select @error('kndk_ids') is-invalid @enderror"
                                multiple
                                required
                                style="min-height: 180px;"
                            >               
                                @foreach($kndks as $kndk)
                                    @php
                                        $code = $kndk->class;
                                        if($kndk->subclass) $code .= '.' . $kndk->subclass;
                                        if($kndk->group) $code .= '.' . $kndk->group;
                                    @endphp
                                    <option value="{{ $kndk->id }}" {{ (is_array(old('kndk_ids')) && in_array($kndk->id, old('kndk_ids'))) ? 'selected' : '' }}>
                                        [{{ $code }}] {{ Str::limit($kndk->name, 90) }} (Документів: {{ $kndk->documents_count ?? 0 }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">Утримуйте <kbd>Ctrl</kbd> (або <kbd>Cmd</kbd> на Mac) для вибору кількох елементів.</div>
                            @error('kndk_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Секція 4: Метадані та Обсяг -->
                    <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">📊 Метадані та Обсяг</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="page_count" class="form-label fw-semibold">Кількість сторінок</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-file-earmark-text"></i></span>
                                <input type="number" name="page_count" id="page_count" class="form-control @error('page_count') is-invalid @enderror" value="{{ old('page_count') }}" min="1">
                            </div>
                            @error('page_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="change_no" class="form-label fw-semibold">№ зміни</label>
                            <input type="text" name="change_no" id="change_no" class="form-control @error('change_no') is-invalid @enderror" value="{{ old('change_no') }}">
                            @error('change_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="storage_location" class="form-label fw-semibold">Місце зберігання</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="storage_location" id="storage_location" class="form-control @error('storage_location') is-invalid @enderror" value="{{ old('storage_location') }}">
                            </div>
                            @error('storage_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Секція 5: Відповідальні особи -->
                    <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">👤 Відповідальні особи</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="author" class="form-label fw-semibold">Розробник / Автор</label>
                            <input type="text" name="author" id="author" class="form-control @error('author') is-invalid @enderror" value="{{ old('author') }}">
                            @error('author') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="approved_by" class="form-label fw-semibold">Затверджено (ким)</label>
                            <input type="text" name="approved_by" id="approved_by" class="form-control @error('approved_by') is-invalid @enderror" value="{{ old('approved_by') }}">
                            @error('approved_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Секція 6: Заміщення та Розповсюдження -->
                    <h5 class="text-primary mb-3 border-bottom pb-2 mt-4">🔄 Заміщення та Розповсюдження</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="replaced_content" class="form-label fw-semibold">Замінений зміст</label>
                            <input type="text" name="replaced_content" id="replaced_content" class="form-control @error('replaced_content') is-invalid @enderror" value="{{ old('replaced_content') }}">
                            @error('replaced_content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="replaced_by" class="form-label fw-semibold">Замінено на (ким/чим)</label>
                            <input type="text" name="replaced_by" id="replaced_by" class="form-control @error('replaced_by') is-invalid @enderror" value="{{ old('replaced_by') }}">
                            @error('replaced_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label_for="distribution" class="form-label fw-semibold">Розсилка / Розповсюдження</label_for>
                            <input type="text" name="distribution" id="distribution" class="form-control @error('distribution') is-invalid @enderror" value="{{ old('distribution') }}">
                            @error('distribution') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12">
                            <label for="note" class="form-label fw-semibold">Примітка</label>
                            <textarea name="note" id="note" rows="3" class="form-control @error('note') is-invalid @enderror" placeholder="Додаткова інформація про документ...">{{ old('note') }}</textarea>
                            @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- Кнопки дій -->
                    <div class="d-flex justify-content-end gap-2 pt-4 mt-4 border-top">
                        <a href="{{ route('documents.index') }}" class="btn btn-light border px-4 fw-semibold">Скасувати</a>
                        <button type="submit" class="btn btn-primary px-5 fw-semibold shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Зберегти документ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript для «живого» пошуку/фільтрації КНДК -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById('search_kndk');
    const selectElement = document.getElementById('kndk_ids');
    const options = Array.from(selectElement.options);

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.toLowerCase().trim();

        options.forEach(option => {
            const text = option.text.toLowerCase();
            // Якщо опція вже обрана користувачем, ми її не ховаємо, щоб зберегти вибір
            if (option.selected || text.includes(searchTerm)) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        });
    });
});
</script>
@endsection
