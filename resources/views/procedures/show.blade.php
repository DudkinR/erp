@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>{{ $procedure->name }}</h2>
        <a href="{{ route('procedures.index') }}" class="btn btn-secondary">
            {{ __('Back') }}
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <strong>{{ __('Description') }}:</strong>
            <p class="mb-0">{{ $procedure->description }}</p>
        </div>
    </div>

    <h4>Кроки процедури</h4>

    <ul id="stepsList" class="list-group mb-3"></ul>  
    <button class="btn btn-primary" onclick="openAddStepModal()">
       + Додати крок
    </button>
    <form action="{{ route('procedures.updateSteps') }}" method="POST">
            @csrf
            <!-- Якщо треба PUT/PATCH/DELETE -->
            {{-- @method('POST') --}}
            <!-- Поле для масиву steps -->
            <input  name="stepsInput" type="text"> 
            <input  name="procedureID" type="hidden" value="{{$procedure->id}}"> 
            <button  class="btn btn-primary" onclick="saveProcedure()">Зберегти процедуру</button>
    </form>
</div>

{{-- МОДАЛЬНЕ ВІКНО --}}
<div class="modal fade" id="stepModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="stepModalTitle">Новий крок</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Тип кроку</label>
                    <select id="modalStepType" class="form-select">
                        <option value="normal">Звичайний</option>
                        <option value="loop">Внутрішній цикл</option>
                        <option value="end">Кінець процедури</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Опис проблеми</label>
                    <textarea id="modalProblem" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Як виправити</label>
                    <textarea id="modalSolution" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Текст для копіювання</label>
                    <textarea id="modalCopyText" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Скасувати</button>
                <button class="btn btn-primary" onclick="saveStepFromModal()">Зберегти</button>
            </div>

        </div>
    </div>
</div>

<script>
let steps = @json($procedure->steps ?? []);
let editIndex = null;
let stepModal;
console.log(steps);
/* INIT */
document.addEventListener('DOMContentLoaded', () => {
    stepModal = new bootstrap.Modal(document.getElementById('stepModal'));
    renderSteps();
});

/* OPEN MODAL */
function openAddStepModal() {
    editIndex = null;
    document.getElementById('stepModalTitle').innerText = 'Новий крок';
    clearModal();
    stepModal.show();
}

function openEditStepModal(index) {
    editIndex = index;
    const step = steps[index];
    document.getElementById('stepModalTitle').innerText = 'Редагування кроку';
    document.getElementById('modalStepType').value = step.type;
    document.getElementById('modalProblem').value = step.problem;
    document.getElementById('modalSolution').value = step.solution;
    document.getElementById('modalCopyText').value = step.copyText;
    stepModal.show();
}

/* SAVE STEP */
function saveStepFromModal() {
    const step = {
        type: modalStepType.value,
        problem: modalProblem.value.trim(),
        solution: modalSolution.value.trim(),
        copyText: modalCopyText.value.trim()
    };

    if (!step.problem || !step.solution) {
        alert('Заповніть обовʼязкові поля');
        return;
    }

    editIndex === null ? steps.push(step) : steps[editIndex] = step;

    stepModal.hide();
    renderSteps();
}

/* RENDER */
function renderSteps() {
    const list = document.getElementById('stepsList');
    list.innerHTML = '';

    if (!steps.length) {
        list.innerHTML = '<li class="list-group-item text-muted">Кроків ще немає</li>';
        return;
    }

    steps.forEach((s, i) => {
        const badge =
            s.type === 'loop' ? '<span class="badge bg-warning ms-2">LOOP</span>' :
            s.type === 'end' ? '<span class="badge bg-danger ms-2">END</span>' : '';

        list.innerHTML += `
            <li class="list-group-item">
                <strong>${i + 1}. ${s.problem}</strong> ${badge}
                <div class="small text-muted">${s.solution}</div>
                <div class="mt-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="copyText(\`${s.copyText}\`)">Копіювати</button>
                    <button class="btn btn-sm btn-outline-warning" onclick="openEditStepModal(${i})">Редагувати</button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteStep(${i})">Видалити</button>
                </div>
            </li>`;
    });
    console.log(steps);
    document.getElementById('stepsInput').value = steps;
}

/* HELPERS */
function deleteStep(i) {
    if (confirm('Видалити крок?')) {
        steps.splice(i, 1);
        renderSteps();
    }
}

function copyText(text) {
    navigator.clipboard.writeText(text);
}

function clearModal() {
    modalStepType.value = 'normal';
    modalProblem.value = '';
    modalSolution.value = '';
    modalCopyText.value = '';
}

/* SAVE TO SERVER */
function saveProcedure() {
    alert(steps);
    console.log(steps);
    // Записуємо масив у приховане поле як JSON
    document.getElementById('stepsInput').value = steps;
    // document.getElementById('procedureForm').submit();
}


</script>


@endsection
