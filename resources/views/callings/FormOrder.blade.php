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
                                  <li> {{ $worker->fio}}</li> 
                                    
                               
                            @endforeach</ul>
                        </td>
                        <td>
                                                                             
                            {{ \Carbon\Carbon::parse($calling->start_time)->format('d.m.Y ') }} 
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($calling->end_time)->diffInHours($calling->start_time) }} {{__('hours')}}
                        </td>
                            

                          
                        <td >
                            <input type="checkbox" name="call_[{{$calling->id}}]" value="{{$calling->id}}" class="form-group" >
                            
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