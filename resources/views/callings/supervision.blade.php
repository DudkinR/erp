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
            <input type="text" id="search" class="form-control" placeholder="{{__('Search')}}">
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Form of callings')}}</h1>
                <a class="btn btn-warning w-100" href="{{ route('callings.create') }}">{{__('New')}}</a>
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
                                    {{ $worker->divisions[0]->name }} <br>
                                    @php $fio =explode(" ", $worker->fio); $fn = $fio[0]; @endphp
                                   <b> {{$fn}}</b>
                                    <br>
                                   <u> {{$worker->phone}} </u>
                                @endif
                                @php $mass_divisions[$worker->divisions[0]->name][]=$worker->fio @endphp
                            @endforeach
                        </td>
                        <td>{{$calling->description}}</td>
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
    <script>
        const search = document.getElementById('search');
        var Vcallings = @json($callings);
        function hideModalWin() {
    $('#modalWin').modal('hide');
}

function ShowModalWin(calling_id) {
    const calling = Vcallings.find(calling => calling.id === calling_id);
    
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