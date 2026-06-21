@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Positions</h1>

    <!-- Пошук -->
    <input
        type="text"
        id="searchInput"
        class="form-control mb-3"
        oninput="MrenderTable(this.value)"
        placeholder="Пошук по всіх полях..."
    >

    <!-- Таблиця -->
    <table class="table table-bordered table-hover" id="positionsTable">
        <thead class="table-light">
            <tr>
                <th>{{ __('ID') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Abv') }}</th>
                <th>{{ __('Start') }}</th>
                <th>{{ __('Data Start') }}</th>
                <th>{{ __('Closed') }}</th>
                <th>{{ __('Data Closed') }}</th>
                <th>{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody id="tableBody"></tbody>
    </table>

    <!-- Пагінація -->
    <div class="d-flex justify-content-between mt-3">
        <button id="prevBtn" class="btn btn-secondary">
            {{ __('Previous') }}
        </button>

        <button id="nextBtn" class="btn btn-secondary">
            {{ __('Next') }}
        </button>
    </div>
</div>

<!-- Модальне вікно -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="editForm">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('Edit Position') }}
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="id"
                        id="editId"
                    >
                    <lable for="editName" class="form-label">
                        {{ __('Name') }}
                    </lable>
                    <input
                        type="text"
                        name="name"
                        id="editName"
                        class="form-control mb-2"
                        placeholder="Name"
                    >
                    <lable for="editDescription" class="form-label">
                        {{ __('Description') }}
                    </lable>
                    <textarea
                        name="description"
                        id="editDescription"
                        class="form-control mb-2"
                        placeholder="Description"
                    ></textarea>    

                    <lable for="editAbv" class="form-label">
                        {{ __('Abbreviation') }}
                    </lable>
                    <input
                        type="text"
                        name="abv"
                        id="editAbv"
                        class="form-control mb-2"
                        placeholder="Abv"
                    >

                    <!-- Пошук підрозділів -->
                     <lable for="divisionSearch" class="form-label">
                        {{ __('Search Division') }}
                    </lable>
                    <input
                        type="text"
                        id="divisionSearch"
                        class="form-control mb-2"
                        placeholder="Пошук підрозділу..."
                    >
                    <label for="editDivision" class="form-label">
                        {{ __('Select Division') }}
                    </label>
                    <select
                        name="division_id[]"
                        id="editDivision"
                        class="form-select"
                        multiple
                    ></select>

                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-success"
                    >
                        {{ __('Save') }}
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<script>
const positions = @json($positions);
const divisions = @json($divisions);

let currentPage = 1;
const pageSize = 10;

// Bootstrap modal
const modalElement = document.getElementById('editModal');
const editModal = new bootstrap.Modal(modalElement);

document.addEventListener('DOMContentLoaded', function () {

    const nameInput = document.getElementById('editName');
    const abvInput = document.getElementById('editAbv');

    if (!nameInput || !abvInput) return;

    function generateAbv(text) {
        return text
            .trim()
            .split(/\s+/)
            .filter(w => w.length > 0)
            .map(w => w[0].toUpperCase())
            .join('');
    }


    editModal.addEventListener('shown.bs.modal', function () {

        if (!abvInput.value.trim() && nameInput.value.trim()) {
            abvInput.value = generateAbv(nameInput.value);
        }
    });

    // автооновлення при зміні назви
    nameInput.addEventListener('input', function () {

        // якщо користувач НЕ редагував ABV вручну
        if (!abvInput.dataset.manual) {
            abvInput.value = generateAbv(nameInput.value);
        }
    });

    // якщо користувач сам змінює ABV — більше не перезаписуємо
    abvInput.addEventListener('input', function () {
        abvInput.dataset.manual = 'true';
    });

});
//
// РЕНДЕР ТАБЛИЦІ
//
function MrenderTable(filter = 'голов') {

    const  tableBody =
        document.getElementById('tableBody');

    tableBody.innerHTML = '';

    const filtered = positions.filter(pos => {

        const text = [
            pos.id ?? '',
            pos.name ?? '',
            pos.description ?? '',
            pos.abv ?? '',
            pos.start ?? '',
            pos.data_start ?? '',
            pos.closed ?? '',
            pos.data_closed ?? ''
        ]
        .join(' ')
        .toLowerCase();

        return text.includes(
            filter.toLowerCase()
        );
    });

    const totalPages =
        Math.ceil(filtered.length / pageSize);

    if (currentPage > totalPages) {
        currentPage = 1;
    }

    const start =
        (currentPage - 1) * pageSize;

    const end =
        start + pageSize;

    const pageItems =
        filtered.slice(start, end);

    pageItems.forEach(pos => {

        const tr =
            document.createElement('tr');

        tr.innerHTML = `
            <td>${pos.id ?? ''}</td>
            <td>${pos.name ?? ''}</td>
            <td>${pos.description ?? ''}</td>
            <td>${pos.abv ?? ''}</td>
            <td>${pos.start ?? ''}</td>
            <td>${pos.data_start ?? ''}</td>
            <td>${pos.closed ?? ''}</td>
            <td>${pos.data_closed ?? ''}</td>

            <td>
                <button
                    class="btn btn-sm btn-primary"
                    onclick="openEdit(${pos.id})"
                >
                    Edit
                </button>
            </td>
        `;

        tableBody.appendChild(tr);
    });
}

// перший рендер
MrenderTable();

// пошук позіцій

document
.getElementById('searchInput')
.addEventListener('input', function() {
    MrenderTable(this.value);
});


//
// ПОШУК ПІДРОЗДІЛІВ
//
document
.getElementById('divisionSearch')
.addEventListener('input', function () {

    const filter =
        this.value.toLowerCase();

    const select =
        document.getElementById('editDivision');

    select.innerHTML = '';

    divisions
        .filter(d =>
            d.name.toLowerCase()
            .includes(filter)
        )
        .forEach(d => {

            const option =
                document.createElement('option');

            option.value = d.id;
            option.textContent = d.name;

            select.appendChild(option);
        });
});

//
// ВІДКРИТИ МОДАЛКУ
//
function openEdit(id) {

    const pos =
        positions.find(p => p.id == id);

    if (!pos) return;

    document.getElementById('editId').value =
        pos.id ?? '';

    document.getElementById('editName').value =
        pos.name ?? '';

    document.getElementById('editDescription').value =
        pos.description ?? '';

    document.getElementById('editAbv').value =
        pos.abv ?? '';

    const select =
        document.getElementById('editDivision');

    select.innerHTML = '';

    divisions.forEach(d => {

        const option =
            document.createElement('option');

        option.value = d.id;
        option.textContent = d.name;

        select.appendChild(option);
    });

    editModal.show();
}

//
// AJAX SAVE
document
.getElementById('editForm')
.addEventListener('submit', function(e){

    e.preventDefault();

    const id =
        document.getElementById('editId').value;

    const formData =
        new FormData(this);

// 👇 ВАЖЛИВО для Laravel
    formData.append('_method', 'PUT');

    fetch(`/positions/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {

        if (!response.ok) {
            const text = await response.text();
            console.error("SERVER ERROR:", text);
            throw new Error("HTTP error " + response.status);
        }

        return response.json();
    })
    .then(data => {

        if (data.success) {

            const idx =
                positions.findIndex(p => p.id == id);

            if (idx !== -1) {
                positions[idx] = data.position;
            }

            MrenderTable(
                document.getElementById('searchInput').value
            );

            editModal.hide();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});
</script>
@endsection