@extends('layouts.app')
@section('content')
<div class="container">

 
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h1>{{__('Постачальники')}}</h1>

            <div class="btn-group">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProviderModal">
                    ➕ Новий постачальник
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#contractModal">
                    📄 Договір
                </button>
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#documentsModal">
                    📂 Документи
                </button>
            </div>
        </div>
    </div>

    {{-- Перемикач режиму пошуку --}}
    <div class="row mb-3">
        <div class="col-md-12 text-center">
            <div class="btn-group" role="group" aria-label="Search Mode">
                <input type="radio" class="btn-check" name="searchMode" id="modeProviders" value="providers" checked>
                <label class="btn btn-outline-primary" for="modeProviders">Постачальники</label>

                <input type="radio" class="btn-check" name="searchMode" id="modeContracts" value="contracts">
                <label class="btn btn-outline-primary" for="modeContracts">Договори</label>

                <input type="radio" class="btn-check" name="searchMode" id="modeDocs" value="documents">
                <label class="btn btn-outline-primary" for="modeDocs">Документи</label>
            </div>
        </div>
    </div>

    {{-- Поле пошуку --}}
    <div class="row mb-3">
        <div class="col-md-6">
            <textarea id="search"></textarea>
        </div>
        <div class="col-md-6" id="answers"></div>
    </div>
</div>

{{-- Стилі --}}
<style>
#search {
    width: 100%;
    height: 150px;
    border: 2px dashed #ccc;
    padding: 10px;
    font-size: 16px;
}
#search.dragover {
    border-color: #007bff;
    background-color: #f0f8ff;
}
.card { cursor: help; }
.card-title { font-size: 1rem; font-weight: 500; }
</style>

{{-- Скрипт --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const providers = @json($provides);
    const contracts = @json($contracts);
    const docs = @json($docs);

    const searchInput = document.getElementById('search');
    const answersDiv = document.getElementById('answers');
    const modeInputs = document.querySelectorAll('input[name="searchMode"]');
    let searchMode = 'providers'; // режим за замовчуванням

    // Перемикання режиму пошуку
    modeInputs.forEach(r => r.addEventListener('change', e => {
        searchMode = e.target.value;
        runSearch();
    }));

    // Загальна функція нормалізації тексту
    function normalize(text) {
        return text?.toLowerCase().replace(/\s+/g, ' ').replace(/[^\wа-яіїєґ0-9 ]/gi, '') || '';
    }

    // Викликається при кожному вводі тексту
    searchInput.addEventListener('input', runSearch);

    function runSearch() {
        if (searchMode === 'providers') runSearchProviders();
        else if (searchMode === 'contracts') runSearchContracts();
        else if (searchMode === 'documents') runSearchDocs();
    }

    // ===== ПОСТАЧАЛЬНИКИ =====
    function runSearchProviders() {
        const query = normalize(searchInput.value.trim());
        const words = query.split(/\s+/).filter(w => w.length > 2);
        const results = providers.map(provider => {
            let score = 0;
            const fields = normalize([
                provider.full_name,
                provider.short_name,
                provider.country,
                provider.products_services,
                provider.ownership_form,
                provider.edrpou_code,
                provider.decision_number,
                provider.decision_date,
                provider.valid_until,
                provider.notes
            ].join(' '));
            words.forEach(word => {
                const matches = fields.match(new RegExp(`${word}`, 'gi'));
                if (matches) score += matches.length;
            });
            return { provider, score };
        });

        const topResults = results.filter(r => r.score > 0)
                                  .sort((a, b) => b.score - a.score)
                                  .slice(0, 10);
        renderResultsProviders(topResults);
    }

    // ===== ДОГОВОРИ =====
    function runSearchContracts() {
        const query = normalize(searchInput.value.trim());
        const words = query.split(/\s+/).filter(w => w.length > 2);
        const results = contracts.map(contract => {
            let score = 0;
            const fields = normalize([
                contract.number,
                contract.date,
                contract.subject,
                contract.notes
            ].join(' '));
            words.forEach(word => {
                const matches = fields.match(new RegExp(`${word}`, 'gi'));
                if (matches) score += matches.length;
            });
            return { contract, score };
        });
        const topResults = results.filter(r => r.score > 0)
                                  .sort((a, b) => b.score - a.score)
                                  .slice(0, 10);
        renderResultsContracts(topResults);
    }

    // ===== ДОКУМЕНТИ =====
    function runSearchDocs() {
        const query = normalize(searchInput.value.trim());
        const words = query.split(/\s+/).filter(w => w.length > 2);
        const results = docs.map(doc => {
            let score = 0;
            const fields = normalize([
                doc.name,
                doc.slug,
                doc.description
            ].join(' '));
            words.forEach(word => {
                const matches = fields.match(new RegExp(`${word}`, 'gi'));
                if (matches) score += matches.length;
            });
            return { doc, score };
        });
        const topResults = results.filter(r => r.score > 0)
                                  .sort((a, b) => b.score - a.score)
                                  .slice(0, 10);
        renderResultsDocs(topResults);
    }

    // ===== ВИВІД РЕЗУЛЬТАТІВ =====
    function renderResultsProviders(results) {
        answersDiv.innerHTML = '';
        if (results.length === 0) return answersDiv.innerHTML = '<p>Нічого не знайдено.</p>';
        results.forEach(({ provider, score }) => {
            const div = document.createElement('div');
            div.classList.add('card', 'mb-2');
            if(provider.status === 1) div.classList.add('bg-light');
            else if(provider.status === 0) div.classList.add('bg-danger');

            const tooltip = `
                Коротка назва: ${provider.short_name || '-'}\n
                Форма власності: ${provider.ownership_form || '-'}\n
                ЄДРПОУ: ${provider.edrpou_code || '-'}\n
                Країна: ${provider.country || '-'}\n
                Продукція: ${provider.products_services || '-'}\n
                Рішення №: ${provider.decision_number || '-'} від ${provider.decision_date || '-'}\n
                Діє до: ${provider.valid_until || '-'}\n
                Примітки: ${provider.notes || '-'}\n
                Релевантність: ${score}
            `.trim();

            div.innerHTML = `<div class="card-body" title="${tooltip.replace(/"/g, '&quot;')}">
                <h5 class="card-title">${provider.full_name}</h5>
            </div>`;
            answersDiv.appendChild(div);
        });
    }

    function renderResultsContracts(results) {
        answersDiv.innerHTML = '';
        if (results.length === 0) return answersDiv.innerHTML = '<p>Договорів не знайдено.</p>';
        results.forEach(({ contract, score }) => {
            const div = document.createElement('div');
            div.classList.add('card', 'mb-2');
            div.innerHTML = `<div class="card-body">
                <h5 class="card-title">Договір №${contract.number}</h5>
                <p>${contract.subject || '-'}<br><strong>${contract.date}</strong> (релевантність: ${score})</p>
            </div>`;
            answersDiv.appendChild(div);
        });
    }

    function renderResultsDocs(results) {
        answersDiv.innerHTML = '';
        if (results.length === 0) return answersDiv.innerHTML = '<p>Документів не знайдено.</p>';
        results.forEach(({ doc, score }) => {
            const div = document.createElement('div');
            div.classList.add('card', 'mb-2');
            div.innerHTML = `<div class="card-body">
                <h5 class="card-title">${doc.name}</h5>
                <p>${doc.description || '-'} (релевантність: ${score})</p>
            </div>`;
            answersDiv.appendChild(div);
        });
    }
});
</script>


{{-- Модальне вікно "Новий постачальник" --}}
<div class="modal fade" id="newProviderModal" tabindex="-1" aria-labelledby="newProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newProviderModalLabel">Новий постачальник</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="newProviderForm" method="POST" action="{{ route('providers.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Повна назва</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Коротка назва</label>
                        <input type="text" class="form-control" name="short_name">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Країна</label>
                        <input type="text" class="form-control" name="country">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Продукція/Послуги</label>
                        <textarea class="form-control" name="products_services"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Форма власності</label>
                        <input type="text" class="form-control" name="ownership_form">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ЄДРПОУ</label>
                        <input type="text" class="form-control" name="edrpou_code">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Рішення №</label>
                        <input type="text" class="form-control" name="decision_number">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата рішення</label>
                        <input type="date" class="form-control" name="decision_date">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Діє до</label>
                        <input type="date" class="form-control" name="valid_until">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Примітки</label>
                        <textarea class="form-control" name="notes"></textarea>
                    </div>  
                    <button type="submit" class="btn btn-primary w-100">Зберегти</button>
                </form>
            </div>
        </div>
    </div>
</div>


{{-- Модальне вікно "Договір" --}}
<div class="modal fade" id="contractModal" tabindex="-1" aria-labelledby="contractModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contractModalLabel">Новий договір</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="contractForm" method="POST" action="{{ route('providers.store_contract') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Номер договору</label>
                        <input type="text" class="form-control" name="number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Дата договору</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Постачальник</label>
                        <select class="form-select" name="provider_id" required>
                            <option value="" disabled selected>Оберіть постачальника</option>
                            @foreach($provides as $provider)
                                <option value="{{ $provider->id }}">{{ $provider->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Предмет договору</label>
                        <textarea class="form-control" name="subject" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Примітки</label>
                        <textarea class="form-control" name="notes"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Зберегти</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Модальне вікно "Документи" --}}
<div class="modal fade" id="documentsModal" tabindex="-1" aria-labelledby="documentsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentsModalLabel">Документи постачальника</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="docsForm" method="POST" action="{{ route('providers.store_document') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Назва документа</label>
                        <input type="text" class="form-control" name="doc_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Опис</label>
                        <textarea class="form-control" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">шифр</label>
                        <input type="text" class="form-control" name="slug" required>
                    </div>
                    <button type="submit" class="btn btn-secondary w-100">Завантажити</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
