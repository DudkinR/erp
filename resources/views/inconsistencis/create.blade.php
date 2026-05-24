@extends('layouts.app')

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
  <div class="modal-dialog modal-lg">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="inconsistencyModalLabel">Створити невідповідність</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Закрити"></button>
      </div>
      <div class="modal-body">
            <div class="mb-3">
                <label for="document_name" class="form-label">Документ</label>
                <textarea class="form-control"  id="document_name" rows="5" class="form-control" readonly></textarea>
            </div>

        <form id="inconsistencyForm" method="POST" action="{{ route('inconsistencis.store') }}">
          @csrf
          <input type="hidden" name="document_inv_no" id="document_inv_no">

        <div class="mb-3">
    <label for="point" class="form-label">Пункт документа</label>
    <input type="text" 
           class="form-control" 
           id="point" 
           name="point"
           data-bs-toggle="tooltip" 
           data-bs-placement="right" 
           title="Вкажіть номер пункту документа, наприклад: 3.2.1">
    <div class="form-text">Введіть номер пункту, який не відповідає вимогам.</div>
    </div>

    <div class="mb-3">
        <label for="current_text" class="form-label">Поточна редакція</label>
        <textarea class="form-control" 
                id="current_text" 
                name="current_text" 
                rows="3"
                data-bs-toggle="tooltip" 
                data-bs-placement="right" 
                title="Скопіюйте текст із документа, який зараз діє."></textarea>
        <div class="form-text">Вкажіть текст пункту у чинній редакції.</div>
    </div>

    <div class="mb-3">
        <label for="proposed_text" class="form-label">Запропонована нова редакція</label>
        <textarea class="form-control" 
                id="proposed_text" 
                name="proposed_text" 
                rows="3"
                data-bs-toggle="tooltip" 
                data-bs-placement="right" 
                title="Напишіть, як ви пропонуєте змінити цей пункт."></textarea>
        <div class="form-text">Опишіть нову редакцію пункту, яку пропонуєте.</div>
    </div>

    <div class="mb-3">
        <label for="reason" class="form-label">Чому не відповідає</label>
        <textarea class="form-control" 
                id="reason" 
                name="reason" 
                rows="3"
                data-bs-toggle="tooltip" 
                data-bs-placement="right" 
                title="Поясніть, чому пункт не відповідає вимогам або стандартам."></textarea>
        <div class="form-text">Опишіть причину невідповідності (посилання на стандарт, норму тощо).</div>
    </div>


          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-plus-circle"></i> Зберегти
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('search').addEventListener('input', function() {
    let q = this.value;
    if (q.length < 3) return;

    fetch("{{ route('inconsistencis.searchdoc') }}?q=" + q)
        .then(res => res.json())
        .then(data => {
            let results = document.getElementById('results');
            results.innerHTML = '';
            data.forEach(doc => {
                let li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action';
                li.textContent = '(' + doc.inv_no + ') '+ doc.short_content +  ' (' + doc.code + ')';
                li.onclick = () => {
                    document.getElementById('document_inv_no').value = doc.inv_no;
                    document.getElementById('document_name').value ='(' + doc.inv_no + ') '+ doc.short_content +  ' (' + doc.code + ')';
                    let modal = new bootstrap.Modal(document.getElementById('inconsistencyModal'));
                    modal.show();
                };
                results.appendChild(li);
            });
        });
});
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endsection
