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
                <h1 class="d-inline-block mr-2">{{ __('Form of callings') }}</h1>
                <form action="/Icallings" method="post" class="form-inline" onsubmit="return validateFilters()">
                    @csrf
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="all_my" value="all_my" @if(($filter ?? '') == 'all_my') checked @endif> {{ __('All my') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="today" value="today" @if(($filter ?? '') == 'today') checked @endif> {{ __('Today') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="week" value="week" @if(($filter ?? '') == 'week') checked @endif> {{ __('Week') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="month" value="month" @if(($filter ?? '') == 'month') checked @endif> {{ __('Month') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="in_sup" value="in_sup" @if(($filter ?? '') == 'in_sup') checked @endif> {{ __('In supervisor') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="in_work" value="in_work" @if(($filter ?? '') == 'in_work') checked @endif> {{ __('In work') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="not_started" value="not_started" @if(($filter ?? '') == 'not_started') checked @endif> {{ __('Not started') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="completed" value="completed" @if(($filter ?? '') == 'completed') checked @endif> {{ __('Completed') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="in_boss" value="in_boss" @if(($filter ?? '') == 'in_boss') checked @endif> {{ __('In boss') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="in_svn" value="in_svn" @if(($filter ?? '') == 'in_svn') checked @endif> {{ __('In SVN') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="in_profcom" value="in_profcom" @if(($filter ?? '') == 'in_profcom') checked @endif> {{ __('In profcom') }}
                    </label>
                    <label class="d-inline-block mr-2">
                        <input type="radio" name="filter" id="in_vonop" value="in_vonop" @if(($filter ?? '') == 'in_vonop') checked @endif> {{ __('In vonop') }}
                    </label>
                    <button type="submit" class="btn btn-success">{{ __('Filter') }}</button>
                </form>
                
            </div>
            
            </div>
            <div class="row">
                <div class="col-md-6">
                    <a class="btn btn-warning w-100" href="{{ route('callings.create') }}">{{__('New')}}</a>
                </div>
                <div class="col-md-6">
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
                        <th>{{__('Call')}}</th>
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
                                @php $mass_divisions[$worker->divisions[0]->name][]=$worker->fio @endphp
                            @endforeach
                        </td>
                        <td>
                            {{$calling->description}}
                            <a href="{{ route('callings.edit', $calling->id) }}" class ="btn w-100 btn-warning">
                            {{__("Edit")}}                                
                            </a>
                            <a href="{{ route('callings.show', $calling->id) }}" class ="btn w-100 btn-info">
                            {{__("Show")}}                                
                            </a>
                            
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
                            <a  class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->start_time)->format('H:i') }}</a>
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

                            <a class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->arrival_time)->format('H:i') }}</a>

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
                            <a   class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->end_time)->format('H:i') }}</a>
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
                    <div class="form-group">
                        <label for="tab_number">{{__('Tab Number of people')}}</label>
                        <input type="number" name="tab_number" id="tab_number" class="form-control"                         
                        onblur="WhatPersonelByTN()">
                        <div id=show_personel></div>

                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description of work')}}</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                    <button class="btn btn-success">{{__('Reserve')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
            function hideModalReserve() {
            $('#modalReserve').modal('hide');
        }
    </script>
@endsection