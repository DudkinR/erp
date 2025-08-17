@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h1>{{__('Personals')}}</h1>
            @if(Auth::user()->hasRole('admin'))
                <a class="btn btn-primary" href="{{ route('personal.create') }}">{{__('Create')}}</a>
            @endif
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="search" class="form-control" placeholder="{{ __('Search') }}">
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12" id="show_personals"></div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-center">
            <nav>
                <ul class="pagination" id="pagination"></ul>
            </nav>
        </div>
    </div>
</div>

<script>
    const personals = @json($personals);
    let show_ps = personals; // копія для відображення
    let currentPage = 1;
    const perPage = 10;

    const show_personals = document.getElementById('show_personals');
    const pagination = document.getElementById('pagination');
    const search = document.getElementById('search');

    function show() {
        show_personals.innerHTML = '';

        let start = (currentPage - 1) * perPage;
        let end = start + perPage;
        let paginatedData = show_ps.slice(start, end);

        if (paginatedData.length === 0) {
            show_personals.innerHTML = `<p class="text-center text-muted">{{__('No results found')}}</p>`;
            pagination.innerHTML = '';
            return;
        }

        let tableHTML = `
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>{{__('FIO')}}</th>
                        <th>{{__('Position')}}</th>
                        <th>{{__('Data')}}</th>
                        @if(Auth::user()->hasRole('admin'))
                        <th>{{__('Action')}}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
        `;

        paginatedData.forEach(personal => {
            tableHTML += `
                <tr>
                    <td>${personal.fio}</td>
                    <td>${personal.positions.map(p => p.name).join(', ')}</td>
                    <td>
                        <strong>{{__('Phones')}}:</strong><br>
                        ${personal.phones.map(ph => ph.phone).join(', ')}
                        <hr>
                        <strong>{{__('Email')}}:</strong><br>
                        ${personal.email ?? ''}
                        <br><strong>{{__('Division')}}:</strong>
                        ${personal.divisions.map(d => d.name).join(', ')}
                    </td>
                    @if(Auth::user()->hasRole('admin'))
                    <td>
                        <a href="/personal/${personal.id}/edit" class="btn btn-info btn-sm w-100 mb-1">Edit</a>
                        <form action="/personal/${personal.id}" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger btn-sm w-100">Delete</button>
                        </form>
                    </td>
                    @endif
                </tr>
            `;
        });

        tableHTML += `</tbody></table>`;
        show_personals.innerHTML = tableHTML;

        renderPagination();
    }

    function renderPagination() {
        pagination.innerHTML = '';
        let totalPages = Math.ceil(show_ps.length / perPage);

        if (totalPages <= 1) return;

        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="goToPage(${i})">${i}</a>
                </li>
            `;
        }
    }

    function goToPage(page) {
        currentPage = page;
        show();
    }

    function findResults() {
        const searchString = search.value.toLowerCase();

        if (searchString.length > 0) {
            show_ps = personals.filter(p => {
                return (
                    (p.fio && p.fio.toLowerCase().includes(searchString)) ||
                    (p.email && p.email.toLowerCase().includes(searchString)) ||
                    (p.positions.some(pos => pos.name.toLowerCase().includes(searchString))) ||
                    (p.divisions.some(div => div.name.toLowerCase().includes(searchString))) ||
                    (p.phones.some(ph => ph.phone.toLowerCase().includes(searchString)))
                );
            });
        } else {
            show_ps = personals;
        }
        currentPage = 1;
        show();
    }

    search.addEventListener('keyup', findResults);

    // перший рендер
    show();
</script>
@endsection
