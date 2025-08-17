@extends('layouts.app')

@section('content')
<style>
  #pagination {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    justify-content: center;
  }
</style>
<div class="container">
    <h1>Packages</h1>
    <div class="row">
        <div class="col-md-12 mb-3">
             @if(Auth::user()->hasRole('quality-engineer','admin'))
            <a href="{{ route('archived-documents.create') }}" class="btn btn-primary">Додати документ</a>
           @endif
            <button id="print-pdf" class="btn btn-secondary"
        onclick="exportPDF()"
        >Друкувати PDF</button>  
        </div>
    </div>
    <input type="text" id="search" placeholder="Пошук пакетів..." class="form-control mb-3">
    <div class="row">
        <div class="col-md-12">
            {{__('Total Packages') }}: <span id="total-packages">{{ count($packages) }}</span>
        </div>
    </div>
    <table class="table table-bordered" id="packages-table">
        <thead>
            <tr>
                <th data-sort="id" style="cursor:pointer">ID пакета</th>
                <th data-sort="foreign_name" style="cursor:pointer">Назва пакета</th>
                <th data-sort="doc_count" style="cursor:pointer">Кількість сторінок</th>
                <th>Документи</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <div class="d-flex justify-content-center mt-3" id="pagination"></div>
</div>
<script>
const packages = @json($packages);

let currentPage = 1;
const rowsPerPage = 100;
let sortColumn = null;
let sortAsc = true;
let searchTerm = "";
let dt = {
    packages: packages
};

function getSortValue(pkg, col) {
    if (col === 'doc_count') {
        return pkg.documents ? pkg.documents.length : 0;
    }
    if (col === 'foreign_name') {
        return (pkg.foreign_name ?? '').toString().toLowerCase();
    }
    return (pkg[col] ?? '').toString().toLowerCase();
}

function renderTablePackages() {
    const tbody = document.querySelector("#packages-table tbody");

    let filtered = packages.filter(pkg => {
        const searchLower = searchTerm.toLowerCase();

        const fieldsMatch = ['id', 'foreign_name'].some(key => {
            return String(pkg[key] ?? '').toLowerCase().includes(searchLower);
        });

        const docsMatch = pkg.documents && pkg.documents.some(doc => 
            String(doc.foreign_name ?? '').toLowerCase().includes(searchLower)
        );

        return fieldsMatch || docsMatch;
    });

    if (sortColumn) {
        filtered.sort((a, b) => {
            let valA = getSortValue(a, sortColumn);
            let valB = getSortValue(b, sortColumn);

            if (valA < valB) return sortAsc ? -1 : 1;
            if (valA > valB) return sortAsc ? 1 : -1;
            return 0;
        });
    }

    const start = (currentPage - 1) * rowsPerPage;
    const pageData = filtered.slice(start, start + rowsPerPage);

    tbody.innerHTML = "";
    pageData.forEach(pkg => {
        const docsHTML = (pkg.documents && pkg.documents.length > 0) 
            ? pkg.documents.map(doc => 
                `<a href="/archived-documents/${doc.id}" target="_blank">${doc.foreign_name ?? 'Документ ' + doc.id}</a>`
              ).join("<br>")
            : "—";

        const row = `
            <tr>
                <td>${pkg.id}</td>
                <td>
                <a href="/archived-packeges/${pkg.id}" target="_blank">
                ${pkg.foreign_name ?? ''}</a>
                </td>
                <td>${pkg.total_pages ?? 0}</td>
                <td>${docsHTML}</td>
            </tr>
        `;
        tbody.innerHTML += row;
    });
    dt = {
        packages: filtered
    };
    document.getElementById('total-packages').textContent = filtered.length;

    renderPagination(filtered.length);
}

function renderPagination(totalItems) {
    const pageCount = Math.ceil(totalItems / rowsPerPage);
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = "";

    for (let i = 1; i <= pageCount; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        btn.className = "btn btn-sm mx-1 " + (i === currentPage ? "btn-primary" : "btn-outline-primary");
        btn.onclick = () => {
            currentPage = i;
            renderTablePackages();
        };
        pagination.appendChild(btn);
    }
}

document.getElementById("search").addEventListener("input", e => {
    searchTerm = e.target.value.trim();
    currentPage = 1;
    renderTablePackages();
});

document.querySelectorAll("#packages-table th[data-sort]").forEach(th => {
    th.style.cursor = "pointer";
    th.addEventListener("click", () => {
        const col = th.getAttribute("data-sort");
        if (sortColumn === col) {
            sortAsc = !sortAsc;
        } else {
            sortColumn = col;
            sortAsc = true;
        }
        renderTablePackages();
    });
});

renderTablePackages();
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
