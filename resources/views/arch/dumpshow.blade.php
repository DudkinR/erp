@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #d8a312ff;
    }
  #pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    justify-content: center;
  }
</style>
<div class="container">
    <h1>Документи</h1>
    <div class="row">
        <div class="col-md-12 mb-3">
            @if(Auth::user()->hasRole('admin'))
            
            <a href="{{ route('archived-documents.load') }}?date={{ $documents->pluck('damp_date')->first() }}" class="btn btn-warning ">Завантажити документи</a>
            @endif
            <a href="{{ route('archived-documents.dump.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>


    <input type="text" id="search" placeholder="Пошук..." class="form-control mb-3">

    <div class="row">
        <div class="col-md-12">
            {{__('Total Documents') }}: <span id="total-documents">{{ count($documents) }}</span>
        </div>
    </div>
   

    <table class="table table-bordered" id="docs-table">
        <thead>
            <tr>
                <th data-sort="id" style="cursor:pointer">ID &#8597;</th>
                <th data-sort="foreign_name" style="cursor:pointer">Назва документа &#8597;</th>
                <th data-sort="reg_date" style="cursor:pointer">Дата реєстрації &#8597;</th>
                <th data-sort="kor" style="cursor:pointer">Кореспондент &#8597;</th>
                <th data-sort="packages" style="cursor:pointer">Пакети (назва, ID)</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <div id="pagination" class="d-flex justify-content-center mt-3"></div>
</div>

<script>
const documents = @json($documents);
let dt = {
    documents: documents
};
let totalDocuments= documents.length;
let currentPage = 1;
const rowsPerPage = 100;
let sortColumn = null;
let sortAsc = true;
let searchTerm = "";
function renderTableAD() {
    const tableBody = document.querySelector('#docs-table tbody');
    let filtered = documents.filter(doc => {
        const docValues = Object.values(doc).map(v => (v ?? '').toString().toLowerCase());
        const packageStr = doc.package 
            ? ((doc.package.foreign_name ?? '') + ' ' + (doc.package.national_name ?? '')).toLowerCase() 
            : '';
        const searchLower = searchTerm.toLowerCase();

        // Пошук по документах або пакеті
        return docValues.some(val => val.includes(searchLower)) || packageStr.includes(searchLower);
    });

    if (sortColumn) {
        filtered.sort((a, b) => {
            let valA, valB;
            if (sortColumn === 'package') {
                valA = a.package ? (a.package.foreign_name ?? '') : '';
                valB = b.package ? (b.package.foreign_name ?? '') : '';
            } else {
                valA = a[sortColumn] ?? '';
                valB = b[sortColumn] ?? '';
            }

            valA = valA.toString().toLowerCase();
            valB = valB.toString().toLowerCase();

            if (valA < valB) return sortAsc ? -1 : 1;
            if (valA > valB) return sortAsc ? 1 : -1;
            return 0;
        });
    }

    const start = (currentPage - 1) * rowsPerPage;
    const pageData = filtered.slice(start, start + rowsPerPage);

    tableBody.innerHTML = '';

    pageData.forEach(doc => {
        const packageHTML = doc.package
            ? `<a href="/archived-packeges/${doc.package.id}" target="_blank">
                ${doc.package.foreign_name ?? ''}
                ${doc.package.national_name ? `(${doc.package.national_name})` : ''}
                <br> (ID: ${doc.package.id})</a>`
            : '–';

        const row = `
            <tr>
                <td>${doc.id}</td>
                <td>
                  <a href="/archived-documents/${doc.id}" target="_blank">                
                    ${doc.foreign_name ?? 'N/A'}
                    ${doc.national_name ? `(${doc.national_name})` : ''}
                  </a>
                </td>
                <td>${doc.reg_date ?? ''}</td>
                <td>${doc.kor ?? ''}</td>
                <td>${packageHTML}</td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
    dt = {
        documents: filtered
    };
    document.getElementById('total-documents').textContent = filtered.length;

    renderPagination(filtered.length);
}
function renderPagination(totalItems) {
    const pageCount = Math.ceil(totalItems / rowsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for(let i=1; i<=pageCount; i++){
        const btn = document.createElement('button');
        btn.textContent = i;
        btn.className = "btn btn-sm mx-1 " + (i === currentPage ? "btn-primary" : "btn-outline-primary");
        btn.onclick = () => {
            currentPage = i;
            renderTableAD();
        };
        pagination.appendChild(btn);
    }
}

document.getElementById('search').addEventListener('input', (e) => {
    searchTerm = e.target.value.trim();
    currentPage = 1;
    renderTableAD();
});

document.querySelectorAll('#docs-table th[data-sort]').forEach(th => {
    th.addEventListener('click', () => {
        const col = th.getAttribute('data-sort');
        if(sortColumn === col) {
            sortAsc = !sortAsc;
        } else {
            sortColumn = col;
            sortAsc = true;
        }
        renderTableAD();
    });
});

renderTableAD();

// send data to route archived-documents.export.pdf
function exportPDF() {
   const url= "{{ route('archived-documents.export.pdf') }}";
  // console.log(dt);
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
       body: JSON.stringify({ data: dt })

    })
    .then(response => {
        if (response.ok) {
            return response.blob();
        }
        throw new Error('Network response was not ok.');
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'archive.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
    })
    .catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}
</script>
@endsection
