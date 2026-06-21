@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ __('Divisions') }}</h1>

    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Пошук...">
<input type="text" id="searchInput" class="form-control mb-3" placeholder="Пошук...">

<div id="divisionTree"></div>

<!-- Bootstrap Modal -->
<div class="modal fade" id="divisionModal" tabindex="-1" aria-labelledby="divisionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="divisionModalLabel">Картка підрозділу</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <h4 id="divisionName"></h4>
        <p id="divisionDescription"></p>
        <p><strong>Батьківський підрозділ:</strong> <span id="divisionParent"></span></p>

        <h5 class="mt-3">Посади</h5>
        <ul class="list-group mb-3" id="divisionPositions"></ul>

        <h5 class="mt-3">Персонал</h5>
        <ul class="list-group mb-3" id="divisionPersonals"></ul>

        <h5 class="mt-3">Системи</h5>
        <ul class="list-group mb-3" id="divisionSystems"></ul>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Закрити</button>
        <a id="divisionShowLink" href="#" class="btn btn-primary">Переглянути</a>
      </div>
    </div>
  </div>
</div>


<script>
    const divisions = @json($divisions);

     // Рекурсивний рендер з кольоровими рівнями
    function renderTree(data, level = 0) {
        let html = '<ul class="list-group">';
        data.forEach(div => {
            // Вибір кольору залежно від рівня
            let colorClass = '';
            switch(level) {
                case 0: colorClass = 'list-group-item-primary'; break; // головний рівень
                case 1: colorClass = 'list-group-item-success'; break; // діти
                case 2: colorClass = 'list-group-item-warning'; break; // внуки
                default: colorClass = 'list-group-item-light'; // глибші рівні
            }

            html += `
                <li class="list-group-item ${colorClass} d-flex justify-content-between align-items-center">
                    <span><strong>${div.name}</strong> — ${div.description ?? ''}</span>
                    <button class="btn btn-sm btn-outline-dark" onclick="openDivisionModal(${div.id})">
                        <i class="bi bi-eye"></i> Картка
                    </button>
                </li>
            `;

            if (div.children && div.children.length > 0) {
                html += '<li class="list-group-item p-0 border-0">';
                html += renderTree(div.children, level + 1);
                html += '</li>';
            }
        });
        html += '</ul>';
        return html;
    }

    function filterTree(data, query) {
        return data
            .map(div => {
                const matches = div.name.toLowerCase().includes(query) ||
                                (div.description && div.description.toLowerCase().includes(query));
                const filteredChildren = div.children ? filterTree(div.children, query) : [];

                if (matches || filteredChildren.length > 0) {
                    return { ...div, children: filteredChildren };
                }
                return null;
            })
            .filter(div => div !== null);
    }

   function openDivisionModal(id) {
        const div = findDivision(divisions, id);
        if (!div) return;

        document.getElementById('divisionName').textContent = div.name;
        document.getElementById('divisionDescription').textContent = div.description ?? '';
        document.getElementById('divisionParent').textContent = div.parent ? div.parent.name : '—';
        document.getElementById('divisionShowLink').href = `/divisions/${div.id}`;

       // Посади
const posList = document.getElementById('divisionPositions');
posList.innerHTML = '';
if (Array.isArray(div.positions)) {
    div.positions.forEach(pos => {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerHTML = `<strong>${pos.name}</strong> — ${pos.description}`;
        posList.appendChild(li);
    });
}

// Персонал
const persList = document.getElementById('divisionPersonals');
persList.innerHTML = '';
if (Array.isArray(div.personals)) {
    div.personals.forEach(pers => {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerHTML = `<strong>${pers.fio}</strong> (${pers.nickname}) — ${pers.status} 
                        <br><small>Email: ${pers.email ?? ''}, Тел: ${pers.phone ?? ''}</small>`;
        persList.appendChild(li);
    });
}

// Системи
const sysList = document.getElementById('divisionSystems');
sysList.innerHTML = '';
if (Array.isArray(div.systems)) {
    div.systems.forEach(sys => {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.textContent = sys.name;
        sysList.appendChild(li);
    });
}
        const modal = new bootstrap.Modal(document.getElementById('divisionModal'));
        modal.show();
    }


    function findDivision(data, id) {
        for (const div of data) {
            if (div.id === id) return div;
            if (div.children) {
                const found = findDivision(div.children, id);
                if (found) return found;
            }
        }
        return null;
    }

    // Початковий рендер
    document.getElementById('divisionTree').innerHTML = renderTree(divisions);

    // Пошук
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.toLowerCase();
        const filtered = filterTree(divisions, query);
        document.getElementById('divisionTree').innerHTML = renderTree(filtered);
    });
</script>


</div>


@endsection
