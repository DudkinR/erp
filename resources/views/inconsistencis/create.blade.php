@extends('layouts.app')

@php
    $document_old = null;
    if (session('document_inv_no')) {
        $document_old = \App\Models\Document::where('inv_no', session('document_inv_no'))->first();
        // echo видалено, щоб не ламати структуру HTML
    }
@endphp

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Пошук документа') }}</h4> 
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="search" class="form-control" placeholder="Введіть шифр, інв. номер або організацію">
                    </div>
                    <ul id="results" class="list-group"></ul>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Modal для другого етапу -->
<div class="modal fade" id="inconsistencyModal" tabindex="-1" aria-labelledby="inconsistencyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="inconsistencyModalLabel">Створити невідповідність</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body">
        
      <!-- Блок вибраних документів (Множинний вибір + Пошук) -->
<div class="mb-4 p-3 bg-light rounded border">
    <label class="form-label fw-bold text-secondary">Вибрані документи для невідповідності:</label>
    
    <!-- Контейнер для значків (баджів) вибраних документів -->
    <div id="selected_documents_container" class="d-flex flex-wrap gap-2 mb-3">
        <!-- Сюди скрипт додаватиме документи у вигляді значків -->
    </div>

    <!-- Нове поле пошуку всередині модалки -->
    <div class="position-relative">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white text-muted"><i class="bi bi-search"></i></span>
            <input type="text" id="modal_search_doc" class="form-control" placeholder="Додати ще документ (введіть шифр, інв. № або назву)...">
        </div>
        <!-- Список результатів пошуку в модалці (з'являється поверх іншого вмісту) -->
        <ul id="modal_search_results" class="list-group position-absolute w-100 shadow-lg d-none" style="z-index: 1055; max-height: 200px; overflow-y: auto;"></ul>
    </div>
</div>


        <form id="inconsistencyForm" method="POST" action="{{ route('inconsistencis.store') }}">
          @csrf
          
          <!-- Контейнер для прихованих інпутів масиву документів document_inv_no[] -->
          <div id="hidden_documents_inputs"></div>

          <!-- Контейнер для динамічних пунктів -->
          <div id="points_container">
              <!-- Блоки пунктів генеруються динамічно через JS -->
          </div>

          <!-- Кнопка додавання нового пункту -->
          <button type="button" class="btn btn-outline-primary btn-sm mb-4 w-100" id="add_point_btn">
              <i class="bi bi-plus-lg"></i> Додати ще один пункт до цих документів
          </button>

          <button type="submit" class="btn btn-success w-100 btn-lg shadow-sm">
            <i class="bi bi-plus-circle"></i> Зберегти невідповідність
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
// Глобальні змінні для збереження стану
let selectedDocuments = [];
let pointIndex = 0;

// ВАШ ПОТОЧНИЙ МЕТОД ПОШУКУ (Оновлено тільки li.onclick)
function performSearch(query) {
    if (query.length < 3) return;

    fetch("{{ route('inconsistencis.searchdoc') }}?q=" + encodeURIComponent(query))
        .then(res => res.json())
        .then(data => {
            let results = document.getElementById('results');
            results.innerHTML = '';
            data.forEach(doc => {
                let li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action';
                li.textContent = '(' + doc.inv_no + ') ' + doc.short_content + ' (' + doc.code + ')';
                
                // Модернізована логіка кліку: додаємо в масив та відкриваємо модалку
                li.onclick = () => {
                    addDocumentToArray(doc);
                    openInconsistencyModal();
                };
                results.appendChild(li);
            });
        });
}

// Функція безпечного додавання документа до глобального масиву (без дублікатів)
function addDocumentToArray(doc) {
    if (!selectedDocuments.some(d => d.inv_no === doc.inv_no)) {
        selectedDocuments.push(doc);
    }
}

// Оновлення баджів документів та hidden-інпутів у формі
function renderSelectedDocuments() {
    const container = document.getElementById('selected_documents_container');
    const hiddenInputs = document.getElementById('hidden_documents_inputs');
    
    container.innerHTML = '';
    hiddenInputs.innerHTML = '';

    if (selectedDocuments.length === 0) {
        container.innerHTML = '<span class="text-muted small">Документи не вибрано. Спробуйте пошук знову.</span>';
        return;
    }

    selectedDocuments.forEach((doc, index) => {
        // 1. Створюємо візуальний значок (badge)
        let badge = document.createElement('div');
        badge.className = 'badge bg-info text-white p-2 d-flex align-items-center gap-2 text-wrap text-start';
        badge.style.maxWidth = '100%';
        badge.innerHTML = `
            <span><strong>(${doc.inv_no})</strong> ${doc.short_content}</span>
            <button type="button" class="btn-close btn-close-white btn-sm" onclick="removeDocument(${index})" style="font-size: 0.65rem;"></button>
        `;
        container.appendChild(badge);

        // 2. Створюємо прихований інпут для передачі масиву в Laravel контролер
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'document_inv_no[]';
        input.value = doc.inv_no;
        hiddenInputs.appendChild(input);
    });
}

// Видалення документа з обраного списку прямо в модалці
function removeDocument(index) {
    selectedDocuments.splice(index, 1);
    renderSelectedDocuments();
}

// Відкриття вікна та генерація першого блоку полів
function openInconsistencyModal() {
    let modalEl = document.getElementById('inconsistencyModal');
    let modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    
    // Якщо пунктів ще немає — створюємо перший автоматично
    if (document.getElementById('points_container').children.length === 0) {
        addPointBlock();
    }
    
    renderSelectedDocuments();
    modal.show();
}

// Динамічне додавання нового блоку полів з повним дублюванням попереднього
function addPointBlock() {
    const container = document.getElementById('points_container');
    
    // Знаходимо всі блоки, які зараз є на екрані
    const existingBlocks = container.querySelectorAll('.point-block');
    
    // Обʼєкт для збереження даних з попереднього блоку
    let prevData = {
        point: '',
        current_text: '',
        proposed_text: '',
        reason: ''
    };

    // Якщо на екрані вже є хоча б один блок, копіюємо його актуальні значення
    if (existingBlocks.length > 0) {
        const lastBlock = existingBlocks[existingBlocks.length - 1];
        
        // Шукаємо інпути всередині останнього блоку за допомогою часткового збігу імені [name*="..."]
        const pointInput = lastBlock.querySelector('input[name*="[point]"]');
        const currentInput = lastBlock.querySelector('textarea[name*="[current_text]"]');
        const proposedInput = lastBlock.querySelector('textarea[name*="[proposed_text]"]');
        const reasonInput = lastBlock.querySelector('textarea[name*="[reason]"]');

        if (pointInput) prevData.point = pointInput.value;
        if (currentInput) prevData.current_text = currentInput.value;
        if (proposedInput) prevData.proposed_text = proposedInput.value;
        if (reasonInput) prevData.reason = reasonInput.value;
    }
    
    let block = document.createElement('div');
    block.className = 'card mb-4 border-primary position-relative shadow-sm point-block';
    block.innerHTML = `
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
            <h6 class="mb-0 text-primary fw-bold">Параметри пункту №${pointIndex + 1}</h6>
            ${pointIndex > 0 ? `<button type="button" class="btn btn-sm btn-outline-danger py-0" onclick="this.closest('.point-block').remove()"><i class="bi bi-trash"></i> Видалити пункт</button>` : ''}
        </div>
        <div class="card-body">
            
            <!-- Поле: Пункт документа -->
            <div class="mb-3">
                <label class="form-label fw-bold small">Пункт документа</label>
                <input type="text" class="form-control" name="points[${pointIndex}][point]" id="point_${pointIndex}" placeholder="Наприклад: 3.1.4" value="${prevData.point}">
                <div class="gap-1 d-flex flex-wrap mt-1">
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('point_${pointIndex}', 'Загальні вимоги')">Загальні вимоги</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('point_${pointIndex}', 'Додаток А')">Додаток А</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('point_${pointIndex}', 'Вступна частина')">Вступна частина</button>
                </div>
            </div>

            <!-- Поле: Чинна редакція -->
            <div class="mb-3">
                <label class="form-label fw-bold small">Чинна редакція</label>
                <textarea class="form-control" name="points[${pointIndex}][current_text]" id="current_${pointIndex}" rows="2" placeholder="Текст чинної редакції...">${prevData.current_text}</textarea>
                <div class="gap-1 d-flex flex-wrap mt-1">
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('current_${pointIndex}', 'Текст відсутній (пункт вводиться вперше)')">Вводиться вперше</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('current_${pointIndex}', 'Редакція застаріла через зміни в законодавстві')">Застаріла</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('current_${pointIndex}', 'Недостатній виклад чинних вимог')">Недостатній виклад</button>
                </div>
            </div>

            <!-- Поле: Запропонована нова редакція -->
            <div class="mb-3">
                <label class="form-label fw-bold small">Запропонована нова редакція</label>
                <textarea class="form-control" name="points[${pointIndex}][proposed_text]" id="proposed_${pointIndex}" rows="2" placeholder="Новий запропонований текст...">${prevData.proposed_text}</textarea>
                <div class="gap-1 d-flex flex-wrap mt-1">
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('proposed_${pointIndex}', 'Викласти в такій редакції: ')">Викласти в редакції</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('proposed_${pointIndex}', 'Доповнити словами: ')">Доповнити словами</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('proposed_${pointIndex}', 'Пункт виключити повністю')">Виключити пункт</button>
                </div>
            </div>

            <!-- Поле: Причина невідповідності -->
            <div class="mb-3">
                <label class="form-label fw-bold small">Чому не відповідає (Причина)</label>
                <textarea class="form-control" name="points[${pointIndex}][reason]" id="reason_${pointIndex}" rows="2" placeholder="Обґрунтування змін...">${prevData.reason}</textarea>
                <div class="gap-1 d-flex flex-wrap mt-1">
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('reason_${pointIndex}', 'Згідно з вимогами Закону України ')">Згідно Закону</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('reason_${pointIndex}', 'Відповідно до проектно-кошторисної документації (ПКД) ')">Згідно ПКД</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('reason_${pointIndex}', 'У звʼязку з виявленою виробничою необхідністю')">Виробнича необхідність</button>
                    <button type="button" class="btn btn-light btn-xs border text-muted" onclick="insertPhrase('reason_${pointIndex}', 'Невідповідність діючим нормам ДСТУ ')">ДСТУ</button>
                </div>
            </div>

        </div>
    `;
    
    container.appendChild(block);
    pointIndex++;
}



// Функція інтелектуальної вставки фраз-шаблонів без затирання тексту
function insertPhrase(fieldId, phrase) {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    // Якщо в полі вже є текст, додаємо пробіл перед додаванням нової фрази
    if (field.value.length > 0 && !field.value.endsWith(' ')) {
        field.value += ' ';
    }
    
    field.value += phrase;
    field.focus(); // Залишаємо фокус на полі для продовження введення
}

// Прив'язка подій
document.getElementById('add_point_btn').addEventListener('click', addPointBlock);

document.getElementById('search').addEventListener('input', function() {
    performSearch(this.value);
});

document.getElementById('search').addEventListener('input', function() {
    performSearch(this.value);
});

document.addEventListener('DOMContentLoaded', function () {
    // Ініціалізація тултіпів
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Логіка Laravel Сесії
    @if(session('document_inv_no'))
        let savedInvNo = "{{ session('document_inv_no') }}";
        document.getElementById('search').value = savedInvNo;
        
        // Автоматично шукаємо цей єдиний документ, додаємо в масив та відкриваємо вікно
        fetch("{{ route('inconsistencis.searchdoc') }}?q=" + encodeURIComponent(savedInvNo))
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    addDocumentToArray(data[0]); // Виправлено: беремо перший елемент масиву data[0]
                    openInconsistencyModal();
                }
            });
    @endif
});

// Пошук документів безпосередньо всередині модального вікна
document.getElementById('modal_search_doc').addEventListener('input', function() {
    let q = this.value;
    let resultsUl = document.getElementById('modal_search_results');
    
    if (q.length < 3) {
        resultsUl.classList.add('d-none');
        return;
    }

    fetch("{{ route('inconsistencis.searchdoc') }}?q=" + encodeURIComponent(q))
        .then(res => res.json())
        .then(data => {
            resultsUl.innerHTML = '';
            
            if (data.length === 0) {
                let li = document.createElement('li');
                li.className = 'list-group-item disabled small text-muted';
                li.textContent = 'Нічого не знайдено';
                resultsUl.appendChild(li);
            } else {
                data.forEach(doc => {
                    let li = document.createElement('li');
                    li.className = 'list-group-item list-group-item-action small py-2';
                    li.style.cursor = 'pointer';
                    li.textContent = '(' + doc.inv_no + ') ' + doc.short_content + ' (' + doc.code + ')';
                    
                    li.onclick = () => {
                        addDocumentToArray(doc);      // Додаємо документ в масив
                        renderSelectedDocuments();   // Оновлюємо баджі та приховані інпути
                        document.getElementById('modal_search_doc').value = ''; // Очищаємо поле
                        resultsUl.classList.add('d-none'); // Ховаємо випадаючий список
                    };
                    resultsUl.appendChild(li);
                });
            }
            resultsUl.classList.remove('d-none');
        });
});

// Ховаємо випадаючий список у модалці, якщо клікнули повз нього
document.addEventListener('click', function(e) {
    let resultsUl = document.getElementById('modal_search_results');
    let searchInput = document.getElementById('modal_search_doc');
    if (resultsUl && e.target !== searchInput && e.target !== resultsUl) {
        resultsUl.classList.add('d-none');
    }
});

</script>

<style>
.btn-xs {
    padding: .15rem .4rem;
    font-size: .72rem;
    border-radius: .2rem;
    background-color: #f8f9fa;
    transition: all 0.15s ease-in-out;
}
.btn-xs:hover {
    background-color: #e9ecef !important;
    color: #198754 !important;
    border-color: #198754 !important;
}
</style>
@endsection