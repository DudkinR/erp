@extends('layouts.app')
@section('content')
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
    <div class="row">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" id="search"  class="form-control" placeholder="{{__('Search')}}">
                <span class="input-group-text" onclick="findResults()">
                    <i class="search_input_button">{{__('Search')}}</i>
                </span>
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Form of callings Supervision')}}</h1>
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <a class="btn btn-warning w-100" href="{{ route('callings.create') }}">{{__('New')}}</a>
            </div>

        <div class="col-md-4">

            <form action="/Icallings" method="post" class="form-inline" onsubmit="return validateFilters()">
                @csrf
                <select name="filter" class="form-control" onchange="sendThisForm(this)">
                    <option value="all">{{ __('All') }}</option>
                    <option value="today" @if(($filter ?? '') == 'today') selected @endif>{{ __('Today') }}</option>
                    <option value="week" @if(($filter ?? '') == 'week') selected @endif>{{ __('Week') }}</option>
                    <option value="month" @if(($filter ?? '') == 'month') selected @endif>{{ __('Month') }}</option>
                    <option value="in_sup" @if(($filter ?? '') == 'in_sup') selected @endif>{{ __('In supervisor') }}</option>
                    <option value="in_work" @if(($filter ?? '') == 'in_work') selected @endif>{{ __('In work') }}</option>
                    <option value="not_started" @if(($filter ?? '') == 'not_started') selected @endif>{{ __('Not started') }}</option>
                    <option value="completed" @if(($filter ?? '') == 'completed') selected @endif>{{ __('Completed') }}</option>
                    <option value="in_boss" @if(($filter ?? '') == 'in_boss') selected @endif>{{ __('In boss') }}</option>
                    <option value="in_svn" @if(($filter ?? '') == 'in_svn') selected @endif>{{ __('In SVN') }}</option>
                    <option value="in_profcom" @if(($filter ?? '') == 'in_profcom') selected @endif>{{ __('In profcom') }}</option>
                    <option value="in_vonop" @if(($filter ?? '') == 'in_vonop') selected @endif>{{ __('In vonop') }}</option>
                </select>
                <button type="submit" class="btn btn-success">{{ __('Filter') }}</button>  

                <script>
                    function validateFilters() {
                        if (document.querySelector('select[name="filter"]').value == '') {
                            alert('{{ __("Choose filter") }}');
                            return false;
                        }
                        return true;
                    }
                </script>
            </form>
        </div>
        <div class="col-md-4">
            <a class="btn btn-info w-100" onclick="$('#modalReserve').modal('show')">{{__('Reserve')}}</a>
        </div>
    </div>  

        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{__('№')}}</th>
                        <th>{{__('Department')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Start')}}</th>
                        <th>{{__('In work')}}</th>
                        <th>{{__('Completed')}}</th>
                        <th>{{__('Number of people')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($callings as $calling)
                    <tr>
                        <td>{{$calling->id}}</td>
                        <td>
                           @php  $mass_divisions=[]; @endphp
                            @foreach($calling->workers as $worker)
                                @if($worker->pivot->worker_type_id == 6)
                                    {{ $worker->divisions[0]->name }} 
                                    <br>
                                    @php $fio =explode(" ", $worker->fio); $fn = $fio[0]; @endphp
                                   <b> {{$fn}}</b>
                                    <br>
                                   <u> {{$worker->phone}} </u>
                                @endif
                                @php
                                if(isset($worker->divisions[0]))
                                 $mass_divisions[$worker->divisions[0]->name][]=$worker->fio;
                                @endphp
                            @endforeach
                        </td>
                        <td>
                            {{$calling->description}}
                            <br>
                            <a onclick="ShowModalWin({{$calling->id}})" class="btn btn-warning" title="{{__('Confirm')}}"> {{__('Confirm')}}</a>
                            
                        </td>
                        <td title="{{ $calling->start_time }}"
                            @if($calling->start_time==null && $calling->personal_start_id==null)
                                style="background-color: #ff8040"
                                @elseif($calling->start_time!==null && $calling->personal_start_id==null)
                                style="background-color: #ffff80"                                
                            @endif
                            >  
                            @if($calling->start_time!==null&& $calling->personal_start_id!==null)                          
                            {{ \Carbon\Carbon::parse($calling->start_time)->format('H:i') }}
                            @elseif($calling->start_time!==null&& $calling->personal_start_id==null)
                            <a onclick="ShowModalWin({{$calling->id}})"  class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->start_time)->format('H:i') }}</a>
                            @else
                            -----
                            @endif
                        </td>
                        <td title="{{ $calling->arrival_time }}"
                            @if($calling->arrival_time==null && $calling->personal_arrival_id==null)
                                style="background-color: #ff8040 "
                                @elseif($calling->arrival_time!==null && $calling->personal_arrival_id==null)
                                style="background-color: ##ffff80"
                            @endif
                            >
                            @if($calling->arrival_time!==null && $calling->personal_arrival_id!==null)

                            {{ \Carbon\Carbon::parse($calling->arrival_time)->format('H:i') }}

                            @elseif($calling->arrival_time!==null && $calling->personal_arrival_id==null)

                            <a onclick="ShowModalWin({{$calling->id}})"  class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->arrival_time)->format('H:i') }}</a>

                            @else
                            ------
                            @endif
                        </td>
                        <td title="{{ $calling->end_time }}"
                            @if($calling->end_time==null && $calling->personal_end_id==null)
                                style="background-color: #ff8040 "
                                @elseif($calling->end_time!==null && $calling->personal_end_id==null)
                                style="background-color: #ffff80"
                            @endif
                            >
                            @if($calling->end_time!==null && $calling->personal_end_id!==null)

                            {{ \Carbon\Carbon::parse($calling->end_time)->format('H:i') }}
                            @elseif($calling->end_time!==null && $calling->personal_end_id==null)
                            <a onclick="ShowModalWin({{$calling->id}})"  class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->end_time)->format('H:i') }}</a>
                            @else
                            ------
                            @endif
                        </td>
                        
                        <td>
                            @foreach($mass_divisions as $key=>$value)
                                <a href="" title="@foreach($value as $v){{$v}} &#13;@endforeach">
                                    {{$key}} -  {{count($value)}}

                                </a>
                            @endforeach

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        
        </div>
    </div>
</div>
<div class="modal" id="modalWin">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Shift supervisor will Confirm this work')}}</h5>
                <button onclick="hideModalWin()" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('callings.confirmStore')}}" method="POST">
                    @csrf
                    <input type="hidden" name="calling_id" id="calling_id">
                    <input type="hidden" name="filter" value="{{$filter ?? ''}}">
                    <div class="form-group">
                        <label for="number">{{__('Number of people')}}</label>
                        <span id="number"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <span id="description"></span>
                    </div>
                    <div class="form-group">
                        <label for="workers">
                            {{__('Workers')}}
                        </label>
                        <ul id="workers">
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="start">
                            {{__('Start')}}
                        </label>
                        <span id="start_show_time"></span>
                        <input type="checkbox" name="start" id="start" value="1">
                    </div>
                    <div class="form-group">
                        <label for="in_work">
                            {{__('In work')}}
                        </label>
                        <span id="in_work_show_time"></span>
                        <input type="checkbox" name="in_work" id="in_work" value="1">
                    </div>
                    <div class="form-group">
                        <label for="completed">
                            {{__('Completed')}}
                        </label>
                        <span id="completed_show_time"></span>
                        <input type="checkbox" name="completed" id="completed" value="1">
                    </div>                    

                    <button class="btn btn-success">{{__('Confirm')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modalReserve">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Reserve form blank')}}</h5>
                <button onclick="hideModalReserve()" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('callings.reserveStore')}}" method="POST">
                    @csrf
                    <input type="hidden" name="calling_id" id="calling_id_reserve">
                    <input type="hidden" name="filter" value="{{$filter ?? ''}}">
                    <div class="form-group">
                        <label for="tab_number">{{__('Tab Number of people')}}</label>
                        <input type="number" name="tab_number" id="tab_number" class="form-control"
                            required                         
                        onblur="WhatPersonelByTN()">
                        <div id=show_personel></div>

                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description of work')}}</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="type_of_work">{{__('Type of work')}}</label>
                        <select name="type_of_work" id="type_of_work" class="form-control" required>
                            @foreach($DI['Vyklyk_na_robotu_ids'] as $Vyklyk_na_robotu_id)
                            <optgroup label="{{$Vyklyk_na_robotu_id->name}}">
                                @foreach($DI['works_names'] as $parent_id=>$work_type)
                                 @if( $Vyklyk_na_robotu_id->id == $parent_id)
                                  @foreach($work_type as $key=>$work) 
                                  <option value="{{$key}}">
                                    {{$work['name']}}
                                    </option>
                                  @endforeach
                                 @endif                                     
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success">{{__('Reserve')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
    const searchInput = document.getElementById('search');
    var Vcallings = @json($callings);
        console.log(Vcallings);
    function hideModalWin() {
            $('#modalWin').modal('hide');
        }
    
    function hideModalReserve() {
            $('#modalReserve').modal('hide');
        }

    function ShowModalWin(calling_id) {
    const calling = Object.values(Vcallings).find(calling => calling.id === calling_id);
    
    // Устанавливаем ID вызова
    document.getElementById('calling_id').value = calling_id;
    
    // Устанавливаем количество работников
    document.getElementById('number').textContent = calling.workers.length;
    
    // Устанавливаем описание
    document.getElementById('description').textContent = calling.description;
    
    // Список работников
    document.getElementById('workers').innerHTML = '';
    calling.workers.forEach(worker => {
        const li = document.createElement('li');
        li.textContent = worker.fio;
        document.getElementById('workers').appendChild(li);
    });

    // Проверяем и отображаем start_time
    if (calling.start_time !== null) {
        document.getElementById('start').checked = true;
        document.getElementById('start_show_time').textContent = new Date(calling.start_time).toLocaleString();
        document.getElementById('start').parentElement.style.display = ''; // Показываем блок с чекбоксом
    } else {
        document.getElementById('start').checked = false;
        document.getElementById('start_show_time').textContent = '';
        document.getElementById('start').parentElement.style.display = 'none'; // Скрываем блок с чекбоксом
    }

    // Проверяем и отображаем arrival_time
    if (calling.arrival_time !== null) {
        document.getElementById('in_work').checked = true;
        document.getElementById('in_work_show_time').textContent = new Date(calling.arrival_time).toLocaleString();
        document.getElementById('in_work').parentElement.style.display = ''; // Показываем блок с чекбоксом
    } else {
        document.getElementById('in_work').checked = false;
        document.getElementById('in_work_show_time').textContent = '';
        document.getElementById('in_work').parentElement.style.display = 'none'; // Скрываем блок с чекбоксом
    }

    // Проверяем и отображаем end_time
    if (calling.end_time !== null) {
        document.getElementById('completed').checked = true;
        document.getElementById('completed_show_time').textContent = new Date(calling.end_time).toLocaleString();
        document.getElementById('completed').parentElement.style.display = ''; // Показываем блок с чекбоксом
    } else {
        document.getElementById('completed').checked = false;
        document.getElementById('completed_show_time').textContent = '';
       document.getElementById('completed').parentElement.style.display = 'none'; // Скрываем блок с чекбоксом
    }

    // Блокировка чекбоксов
 //   document.getElementById('start').disabled = calling.start_time !== null;
   // document.getElementById('in_work').disabled = calling.arrival_time !== null;
  //  document.getElementById('completed').disabled = calling.end_time !== null;

    // Обработчики изменения состояний чекбоксов
    document.getElementById('start').addEventListener('change', (e) => {
        document.getElementById('in_work').disabled = !e.target.checked;
        document.getElementById('completed').disabled = !e.target.checked;
    });
    
    document.getElementById('in_work').addEventListener('change', (e) => {
        document.getElementById('completed').disabled = !e.target.checked;
    });

    // Открываем модальное окно
    $('#modalWin').modal('show');
}
    search.addEventListener('keyup', (e) => {
            const value = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                row.querySelector('td').textContent.toLowerCase().includes(value) ? row.style.display = '' : row.style.display = 'none';
            });
        }); 
        
       
    
    </script>
@endsection