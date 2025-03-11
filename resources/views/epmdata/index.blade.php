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
            <h1>{{__('epmdata')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('epmdata.create') }}">{{__('Create')}}</a>
            </div>
        </div>   
        <div class="row">
            <div class="col-md-12">
            @php
             $divisions = \App\Models\Division::all()->keyBy('id'); // Створюємо масив за ID
            @endphp

            @foreach($epmdata as $date => $epms)
                @php
                    $date = \Carbon\Carbon::parse($date)->format('d-m-Y');
                    $blocked = 1;
                    $completed = 1;
                    $divisions_name = [];
                @endphp

            @foreach ($epms as $epm)
                    @php
                        if ($epm->blocked == 0) {
                            $blocked = 0;
                        }
                        if (!$epm->value) {
                            $completed = 0;
                            $epmd = \App\Models\EPM::find($epm->id); // Спрощений запит

                            if ($epmd && $epmd->division) { // Перевіряємо, чи не NULL
                                if (!array_key_exists($epmd->division, $divisions_name)) {
                                    $divisions_name[$epmd->division] = 1;                                  
                                }
                                else {
                                    $divisions_name[$epmd->division]++;
                                }
                            }
                        }
                    @endphp
            @endforeach
        <div class="card">
            <div class="card-body @if($completed == 0) bg-danger @endif">
                <h5 class="card-title">{{ $date }}</h5>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($divisions_name as $div_id=>$div_count)
                    <li class="list-group">
                        <div class="card-body">
                            <h5 class="card-title"> 
                                {{ $divisions[$div_id]->name }}:
                                {{ $div_count }} 
                                <a href="{{ route('epmdata.load', ['date' => $date, 'division' => $div_id]) }}" class="btn btn-light">Add</a>
                                
                            </h5>
                            </div>
                    </li>
                @endforeach
            </ul>

        </div>
    @endforeach



            </div>
        </div>

    </div>
@endsection