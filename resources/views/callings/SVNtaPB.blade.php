@extends('layouts.app')
@section('content')
@php $alarm_position=['керевник','начальник','руководитель','директор']; @endphp
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
            <h3>{{__('Form of callings')}}</h3>
            <h1>{{__('SVNtaPB')}}</h1>
            </div>
        </div>    
        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{__('№')}}</th>
                        <th>{{__('Department')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Start data')}}</th>
                        <th>{{__('Hours')}}</th>
                        <th>{{__('Action')}}</th>
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
                        <td>
                            <p style = "font-size: 20px;">
                            {{$calling->description}}
                            </p>
                            <br>
                            <ul>
                            @foreach($calling->workers as $worker)                               
                            @php 
                            $isAlarm = false;                                         
                                        foreach ($alarm_position as $word) {                                    
                                            if (stripos($worker->positions[0]['name'], $word) !== false) {
                                                $isAlarm = true;                                    
                                                break;
                                            }
                                        }  
                                $start = \Carbon\Carbon::parse($worker->pivot->start_time);
                                $end = \Carbon\Carbon::parse($worker->pivot->end_time);
                                $diffInMinutes = $start->diffInMinutes($end); // Total difference in minutes
                                $hours = floor($diffInMinutes / 60); // Get the number of hours
                                $minutes = $diffInMinutes % 60;      // Get the remaining minutes             
                                    @endphp                               
                                    <li class="{{ $isAlarm ? 'bg-warning' : 'bg-light' }}">
                                        <b>{{ $worker->fio }}</b>
                                        ({{ $worker->positions[0]->name }})    
                                        <b>  {{ sprintf('%02d', $hours) }}:{{ sprintf('%02d', $minutes) }}  </b>   
                                    </li> 
                               
                            @endforeach</ul>
                        </td>
                        <td>
                                                                             
                            {{ \Carbon\Carbon::parse($calling->start_time)->format('d.m.Y ') }} 
                        </td>
                        <td>
                        @php
                                $start = \Carbon\Carbon::parse($calling->start_time);
                                $end = \Carbon\Carbon::parse($calling->end_time);
                                $diffInMinutes = $start->diffInMinutes($end); // Total difference in minutes
                                $hours = floor($diffInMinutes / 60); // Get the number of hours
                                $minutes = $diffInMinutes % 60;      // Get the remaining minutes
                            @endphp

                            {{ sprintf('%02d', $hours) }}:{{ sprintf('%02d', $minutes) }}    </td>
                            

                          
                        <td >
                            <button onclick="ShowModalWin({{$calling->id}})" class="btn btn-success w-100">{{__('Confirm')}}</button>
                            <a href="{{route('callings.show',$calling)}}"" class="btn btn-warning w-100"> {{__('Show')}} </a>
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
                <h5 class="modal-title">{{__('Boss will Confirm this work')}}</h5>
                <button onclick="hideModalWin()" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('callings.confirmSS')}}" method="POST">
                    @csrf
                    <input type="hidden" name="calling_id" id="calling_id" >
                    <input type="hidden" name="tp_check" value="SVNtaPB">
                    <input type="hidden" name="checkin_type_id" id="checkin_type_id" value="75">
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
                    </div>
                    <div class="form-group">
                        <label for="in_work">
                            {{__('In work')}}
                        </label>
                        <span id="in_work_show_time"></span>
                     
                    </div>
                    <div class="form-group">
                        <label for="completed">
                            {{__('Completed')}}
                        </label>
                        <span id="completed_show_time"></span>                        
                    </div> 
         
                    <div class="form-group">
                        <label for="comment">{{__('Comment')}}</label>
                        <textarea name="comment" id="comment" class="form-control"
                        onchange="document.getElementById('comment_reject').value = this.value"
                        ></textarea>
                    </div> 
                    <div class="row">
                        <div class="col-md-6">
                          <button class="btn btn-success w-100">{{__('Confirm')}}</button>
                  
                        </form>  
                        </div>
                        <div class="col-md-6">
                           <form action="{{route('callings.rejectSS')}}" method="POST" >  
                                @csrf
                                <input type="hidden" name="comment" id="comment_reject">
                                <input type="hidden" name="calling_id" id="calling_idrj" >
                                <input type="hidden" name="checkin_type_id" id="checkin_type_id" value="78">
                                <button type="submit"  class="btn btn-danger w-100">{{__('Reject')}}</button>
                            </form> 
                        </div>
                        
                    </div>      

                    
            

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
    const calling = Object.values(Vcallings).find(calling => calling.id === calling_id);
    
    // Устанавливаем ID вызова
    document.getElementById('calling_id').value = calling_id;
    document.getElementById('calling_idrj').value = calling_id;
    
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
    document.getElementById('start_show_time').textContent = new Date(calling.start_time).toLocaleString();
    document.getElementById('in_work_show_time').textContent = new Date(calling.arrival_time).toLocaleString();
    document.getElementById('completed_show_time').textContent = new Date(calling.end_time).toLocaleString();

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