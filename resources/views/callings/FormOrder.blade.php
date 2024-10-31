@extends('layouts.app')
@section('content')
@php $alarm_position=['керевник','начальник','руководитель','директор','головного інженера','головной інженер']; @endphp
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
    <form action="{{route('callings.order')}}" method="POST">
                    @csrf
        <div class="row">
            <div class="col-md-12">
            <h3>{{__('For order')}}</h3>
            <h1>{{__('VONtaOP')}}</h1>
            </div>
        </div>  
        <div class="row">
            <div class="col-md-12">
                <a href="{{route('callings.printOrder')}}" class="btn btn-light w-100">{{__('Print')}}</a>

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
                        <th>{{__('To sequnce')}}</th>
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

                            {{ sprintf('%02d', $hours) }}:{{ sprintf('%02d', $minutes) }}   </td>
                            
                        </td>
                        <td >
                            <input type="checkbox" name="call_[{{$calling->id}}]" value="{{$calling->id}}" class="form-group" >
                            <a href="{{ route('callings.show', $calling->id) }}" class ="btn w-100 btn-info">
                            {{__("Show")}}                                
                            </a>
                         </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-success w-100">{{__('Sequence')}}</button>
            </form>
        
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

        </div>
    </div>
</div>
    <script>
        const search = document.getElementById('search');
        var Vcallings = @json($callings);
        function hideModalWin() {
    $('#modalWin').modal('hide');
}



    </script>
@endsection