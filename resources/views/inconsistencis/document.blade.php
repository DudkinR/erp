@extends('layouts.app')
@php
    $document_old = null;
    if (session('document_inv_no')) {
        $document_old = \App\Models\Document::where('inv_no', session('document_inv_no'))->first();
    }
@endphp

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('Пошук документа') }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="search" class="form-control" value="@if(session('document_inv_no')) {{ $document_old->short_content }}  @endif " placeholder="Введіть шифр, інв. номер або організацію">
                    </div>
                    <ul id="results" class="list-group"></ul>
                </div>
            </div>

            <div id="doc-info" class="card shadow-sm d-none">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Інформація про документ</h5>
                </div>
                <div class="card-body">
                    <div id="doc-details"></div>
                    <hr>
                    <h6>Зауваження:</h6>
                    <div id="doc-inconsistencies"></div>
                </div>
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
                li.onclick = () => loadDocInfo(doc.inv_no);
                results.appendChild(li);
            });
        });
});
function loadDocInfo(inv_no) {
    // сховати список результатів
    document.getElementById('results').innerHTML = '';

    fetch(`/documentId/${inv_no}`)
        .then(res => res.json())
        .then(data => {
            // показати блок з інформацією
            document.getElementById('doc-info').classList.remove('d-none');

            // деталі документа
            let doc = data.document;
            document.getElementById('doc-details').innerHTML = `
                <p><strong>${doc.short_content}</strong></p>
                <p>Шифр: ${doc.code}</p>
                <p> <a href="/document_show/${doc.inv_no}">Подивитись</a>
                            </p>
                <p>Організація: ${doc.organization}</p>
                <p>Тип: ${doc.doc_type}</p>
                <p>Дата реєстрації: ${doc.registration_date}</p>
            `;

            // зауваження
            let incBlock = document.getElementById('doc-inconsistencies');
            incBlock.innerHTML = '';
            if (data.inconsistencies.length === 0) {
                incBlock.innerHTML = '<p class="text-muted">Зауважень немає.</p>';
            } else {
                data.inconsistencies.forEach(inc => {
                    incBlock.innerHTML += `
                        <div class="border rounded p-2 mb-3">
                            <span class="badge bg-secondary">Статус: ${inc.status}</span>
                            <p><strong>Пункт:</strong> ${inc.point}</p>
                            <p><strong>Стара редакція:</strong> ${inc.current_text}</p>
                            <p><strong>Запропонована редакція:</strong> ${inc.proposed_text}</p>
                            <p><strong>Причина:</strong> ${inc.reason}</p>

                            <form id="qaForm-${inc.id}" class="mt-2">
                                <input type="hidden" name="inconsistency_id" value="${inc.id}">
                                <div class="mb-2">
                                    <select class="form-select" name="action">
                                        <option value="fixed">Виконано</option>
                                        <option value="return">Повернути до СЯ</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <textarea class="form-control" name="comment" rows="2" placeholder="Коментар..."></textarea>
                                </div>
                                <button type="button" class="btn btn-success btn-sm" onclick="sendQaResult(${inc.id})">Відправити</button>
                            </form>
                        </div>
                    `;
                });
            }
        });
}

</script>
@endsection
