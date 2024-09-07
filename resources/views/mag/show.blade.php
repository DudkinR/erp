@extends('layouts.app')
@section('content')
@php 
    $columns = $magtable->magcolumns->sortBy('pivot.number');
    $Chart = $columns->contains(fn($column) => $column->type == 2 || $column->type == 3);
@endphp

<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
    <!-- Навигация -->
    <div class="row mb-3">
        <div class="col-md-12 text-end">
            <a href="{{ route('mag.index') }}" class="btn btn-light">{{ __('Back') }}</a>
        </div>  
    </div>

    <!-- Информация о таблице и кнопка "Chart" -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 title="{{ $magtable->description }}">{{ $magtable->name }}</h1>
                    @if($Chart)
                        <a href="{{ route('mag.chart', $magtable) }}" class="btn btn-success">{{ __('Chart') }}</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group" id="searchForm">
                        <input type="text" class="form-control" id="search" placeholder="{{ __('Search') }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Кнопка для вызова модального окна -->
    <div class="row my-3">
        <div class="col-md-12">
            <button type="button" class="btn bg-blue-200 w-100" data-bs-toggle="modal" data-bs-target="#addRowModal">
                {{ __('Add Row') }}
            </button>
        </div>
    </div>

    <!-- Таблица с колонками -->
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <tr> 
                         <th>{{ __('Date time') }}</th>
                        @foreach($columns as $column)
                        @php $clms[] = $column->id; @endphp
                            <th>
                                {{ $column->name }}<br>
                                <small class="text-muted">{{ $column->dimensions }}</small>
                            </th>
                        @endforeach
                      
                    </tr>
                </thead>
                <tbody id="dataTableBody">
                    <!-- Тут будут строки -->
                </tbody>
                
                
            </table>
        </div>
    </div>
</div>

<!-- Модальное окно для добавления строки -->
<div class="modal fade" id="addRowModal" tabindex="-1" aria-labelledby="addRowModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRowModalLabel">{{ __('Добавить строку') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('mag.storeRow', $magtable) }}" method="POST">
                    @csrf
                    <input type="hidden" name="magtable_id" value="{{ $magtable->id }}">

                    @foreach($columns as $column)
                        <div class="form-group mb-3">
                            <label for="column[{{ $column->id }}]" class="form-label fw-bold">{{ $column->name }}</label>
                            
                            @switch($column->type)
                                @case(0)
                                    <textarea name="column[{{ $column->id }}]" class="form-control" rows="3" placeholder="Enter text..."></textarea>
                                    @break
                                @case(1)
                                    <input type="text" name="column[{{ $column->id }}]" class="form-control" placeholder="Enter text...">
                                    @break
                                @case(2)
                                    <input type="number" name="column[{{ $column->id }}]" class="form-control" placeholder="Enter number...">
                                    @break
                                @case(3)
                                    <input type="number" step="0.01" name="column[{{ $column->id }}]" class="form-control" placeholder="0.00">
                                    @break
                                @case(4)
                                    <input type="datetime-local" name="column[{{ $column->id }}]" class="form-control">
                                    @break
                                @case(5)
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="column[{{ $column->id }}]" class="form-check-input" value="true" id="radioTrue{{ $column->id }}">
                                        <label class="form-check-label" for="radioTrue{{ $column->id }}">{{ __('True') }}</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" name="column[{{ $column->id }}]" class="form-check-input" value="false" id="radioFalse{{ $column->id }}">
                                        <label class="form-check-label" for="radioFalse{{ $column->id }}">{{ __('False') }}</label>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">{{ __('Save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    // Example data (replace with @json($rows) and @json($clms) in your Blade template)
    const data = @json($rows); // Example: {"2024-09-07 16:23:12": [{"col": 10, "data": "1", "worker_tn": 13344}, ...]}
    const columns = @json($clms); // Example: [10, 11]

    const tableBody = document.getElementById('dataTableBody');
    const tableHead = document.querySelector('thead tr');



    // Populate table rows
    for (const [date, entries] of Object.entries(data)) {
        let row = `<tr><td>${date}</td>`;

        // Fill data for each column
        columns.forEach(column => {
            const entry = entries.find(e => e.col === column);
            row += `<td>${entry ? entry.data : ''}</td>`;
        });

        row += '</tr>';
        tableBody.innerHTML += row;
    }

    // Search functionality
    document.getElementById('search').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        const rows = tableBody.getElementsByTagName('tr');

        for (const row of rows) {
            let found = false;
            for (const cell of row.getElementsByTagName('td')) {
                if (cell.innerText.toLowerCase().includes(search)) {
                    found = true;
                    break;
                }
            }
            row.style.display = found ? '' : 'none';
        }
    });
</script>
@endsection
