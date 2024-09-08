@extends('layouts.app')
@section('content')
@php 
    $columns = $magtable->magcolumns->sortBy('pivot.number');
    $Chart = $columns->contains(fn($column) => $column->type == 2 || $column->type == 3);
@endphp

<div class="container">
           @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
    <div class="alert alert-success">{{ __(session('success')) }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ __(session('error')) }}</div>
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
            <button type="button" class="btn bg-blue-300 w-100" data-bs-toggle="modal" data-bs-target="#addRowModal">
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
                        @php $Climits = []; @endphp
                        @foreach($columns as $column)
                            @php 
                                $clms[] = $column->id; 
                                $text_limits = "";
                            @endphp
                            <th>
                                {{ $column->name }}<br>
                                <small class="text-muted">{{ $column->dimensions }}</small>
                                <br>
                                @php
                                if ($column->maglimits()->count() > 0) {
                                    // Получаем ограничения и первый элемент из коллекции
                                    $limits = $column->maglimits();
                                    $limit = $limits->first();
    
                                    // Начинаем формировать текст ограничений
                                    $text_limits = "<small class=\"text-muted\">";
    
                                    // Проверяем и добавляем границы с соответствующими значениями и ссылками, если они есть
                                    if (!empty($limit->hfb)) {
                                        $Climits[$column->id]['hfb'] = $limit->hfb; 
                                        $text_limits .= " ВФГ(" . htmlspecialchars($limit->hfb) . ")";
                                        if (!empty($limit->hfb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->hfb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    if (!empty($limit->heb)) {
                                        $Climits[$column->id]['heb'] = $limit->heb;
                                        $text_limits .= " ВАГ(" . htmlspecialchars($limit->heb) . ")";
                                        if (!empty($limit->heb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->heb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    if (!empty($limit->hrb)) {
                                        $Climits[$column->id]['hrb'] = $limit->hrb;
                                        $text_limits .= " ВРГ(" . htmlspecialchars($limit->hrb) . ")";
                                        if (!empty($limit->hrb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->hrb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    if (!empty($limit->hwb)) {
                                        $Climits[$column->id]['hwb'] = $limit->hwb;
                                        $text_limits .= " ВРБ(" . htmlspecialchars($limit->hwb) . ")";
                                        if (!empty($limit->hwb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->hwb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    if (!empty($limit->lwb)) {
                                        $Climits[$column->id]['lwb'] = $limit->lwb;
                                        $text_limits .= " НРБ(" . htmlspecialchars($limit->lwb) . ")";
                                        if (!empty($limit->lwb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->lwb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    if (!empty($limit->lrb)) {
                                        $Climits[$column->id]['lrb'] = $limit->lrb;
                                        $text_limits .= " НРГ(" . htmlspecialchars($limit->lrb) . ")";
                                        if (!empty($limit->lrb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->lrb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    if (!empty($limit->leb)) {
                                        $Climits[$column->id]['leb'] = $limit->leb;
                                        $text_limits .= " НАГ(" . htmlspecialchars($limit->leb) . ")";
                                        if (!empty($limit->leb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->leb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    if (!empty($limit->lfb)) {
                                        $Climits[$column->id]['lfb']=$limit->lfb;
                                        $text_limits .= " НФГ(" . htmlspecialchars($limit->lfb) . ")";
                                        if (!empty($limit->lfb_doc_id)) {
                                            $text_limits .= " <a href='" . htmlspecialchars($limit->lfb_doc_id) . "'>Док</a>";
                                        }
                                    }
    
                                    $text_limits .= "</small>";
                                }
                                @endphp
                                {!! $text_limits !!}
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

    var data = @json($rows); 
    console.log(data);
    const columns = @json($clms); 
    const tableBody = document.getElementById('dataTableBody');
    const tableHead = document.querySelector('thead tr');
    const climits = @json($Climits);
    
function getLimitColor(columnId, value) {
    const limits = climits[columnId];
    const lowNameLimits = ['lwb', 'lrb', 'leb', 'lfb'];
    const highNameLimits = ['hwb', 'hrb', 'heb', 'hfb'];
    const colorLimits = {
        'lwb': 'blue',
        'lrb': 'yellow',
        'leb': 'red',
        'lfb': 'grey',
        'hwb': 'blue',
        'hrb': 'yellow',
        'heb': 'red',
        'hfb': 'grey'
    };

    let  color = '';
    if (limits) {
        console.log(`Processing columnId: ${columnId} with limits:`, limits);
        // Проверяем нижние пределы
        lowNameLimits.forEach( limitType => {
            console.log('type:', limits[limitType]);
            if (limits[limitType]){
                if(value<=limits[limitType]){
                color = colorLimits[limitType];}
            }           
        }
        );
        // Если цвет не установлен после проверки нижних пределов, проверяем верхние пределы
        if (color === '') {
            highNameLimits.forEach( limitType => {
                if (limits[limitType] ){
                    if(value>=limits[limitType]){
                    color = colorLimits[limitType];}
                }           
                }
            );
        }
        // Если цвет всё ещё не установлен, назначаем цвет по умолчанию
        if (color === '') {
            color = 'white';
        }
    } else {
        // Цвет по умолчанию, если нет данных для колонки
        color = 'white';
    }
    console.log(`Determined color: ${color}`);
    return color;
}





    // Populate table rows
    for (const [date, entries] of Object.entries(data)) {
        let row = `<tr><td>${date}</td>`;

        // Fill data for each column
        //console.log(climits);

        columns.forEach(column => {
            const entry = entries.find(e => e.col === column);
            let color = '';
      
         if (entry && climits[column]) { 
                color = `style=" background-color: ${getLimitColor(column, entry.data)}"`;
              

        }

         row += `<td ${color}>${entry ? entry.data : ''}</td>`;
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
