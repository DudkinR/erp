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
                            <a href="{{ route('callings.edit', $calling->id) }}">
                                
                            {{$calling->description}}
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
    <script>
    </script>
@endsection