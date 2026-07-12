@extends('layouts.app')
@section('content')
<div class="container">

    <h2 class="mb-4">Редагування роботи</h2>

    <form action="{{ route('constructionjobs.update', $job->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Об’єкт/Захід</label>
                <input type="text" name="basis" class="form-control" value="{{ old('basis', $job->basis) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Будівля</label>
                <input type="text" name="build" class="form-control" value="{{ old('build', $job->build) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Приміщення</label>
                <input type="text" name="room" class="form-control" value="{{ old('room', $job->room) }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label class="form-label">Вісі/координати</label>
                <input type="text" name="location_axes" class="form-control" value="{{ old('location_axes', $job->location_axes) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Елемент</label>
                <input type="text" name="element" class="form-control" value="{{ old('element', $job->element) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Вид робіт</label>
                <input type="text" name="work_type" class="form-control" value="{{ old('work_type', $job->work_type) }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                <label class="form-label">Одиниця</label>
                <input type="text" name="unit" class="form-control" value="{{ old('unit', $job->unit) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Кількість</label>
                <input type="number" step="0.01" name="q" class="form-control" value="{{ old('q', $job->q) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Тип</label>
                <input type="text" name="type" class="form-control" value="{{ old('type', $job->type) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">Рік</label>
                <input type="number" name="year" class="form-control" value="{{ old('year', $job->year) }}">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-2">
                <label class="form-label">Людино‑години (WHH)</label>
                <div id="whh-info"></div>
                <input type="number" step="0.01" name="whh" id="whh" class="form-control" value="{{ old('whh', $job->whh) }}">
            </div>
        </div>

        <h5 class="mt-4">Місяці (людино‑години)</h5>
        <div class="row mb-3">
            @foreach(['jan'=>'Січень','feb'=>'Лютий','mar'=>'Березень','apr'=>'Квітень','may'=>'Травень','jun'=>'Червень','jul'=>'Липень','aug'=>'Серпень','sep'=>'Вересень','oct'=>'Жовтень','nov'=>'Листопад','dec'=>'Грудень'] as $m=>$label)
            <div class="col-md-2 mb-2">
                <label class="form-label">{{ $label }}</label>
                <input type="number" step="0.01" name="{{ $m }}" id="{{ $m }}" class="form-control month-input" value="{{ old($m, $job->$m) }}">
            </div>
            @endforeach
        </div>

        <div class="mb-3">
            <label class="form-label">Матеріали</label>
            <textarea name="tmc" class="form-control" rows="2">{{ old('tmc', $job->tmc) }}</textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Інвентарний №</label>
                <input type="text" name="inv_no" class="form-control" value="{{ old('inv_no', $job->inv_no) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Цех/підрозділ</label>
                <input type="text" name="own_division" class="form-control" value="{{ old('own_division', $job->own_division) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Грант/код</label>
                <input type="text" name="grant" class="form-control" value="{{ old('grant', $job->grant) }}">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Локальні примітки</label>
            <textarea name="note_locale" class="form-control" rows="2">{{ old('note_locale', $job->note_locale) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Примітки</label>
            <textarea name="note" class="form-control" rows="3">{{ old('note', $job->note) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Оновити</button>
        <a href="{{ route('constructionjobs.index') }}" class="btn btn-secondary">Скасувати</a>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const whhInput = document.getElementById('whh');
    const monthInputs = document.querySelectorAll('.month-input');
    const whhInfo = document.getElementById('whh-info');

    function checkSum() {
        let sum = 0;
        monthInputs.forEach(inp => sum += parseFloat(inp.value) || 0);
        const whh = parseFloat(whhInput.value) || 0;
        const diff = whh - sum;

        if (diff !== 0) {
            whhInput.classList.add('is-invalid');
            whhInput.style.backgroundColor = '#f8d7da';
            monthInputs.forEach(inp => inp.style.backgroundColor = '#f8d7da');
            whhInfo.textContent = `⚠️ WHH (${whh.toFixed(2)}) ≠ сума місяців (${sum.toFixed(2)}). Різниця: ${diff > 0 ? '+' : ''}${diff.toFixed(2)}`;
        } else {
            whhInput.classList.remove('is-invalid');
            whhInput.style.backgroundColor = '';
            monthInputs.forEach(inp => inp.style.backgroundColor = '');
            whhInfo.textContent = '';
        }
    }

    whhInput.addEventListener('input', checkSum);
    monthInputs.forEach(inp => inp.addEventListener('input', checkSum));
});
</script>
@endsection
