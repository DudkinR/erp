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
                <a class="btn btn-info w-100" href="{{ route('epmdata.info') }}">{{__('Analises')}}</a>
                @if(Auth::user()->hasRole('admin'))     
                <a class="btn btn-light w-100" href="{{ route('epmdata.create') }}">{{__('Create')}}</a>
                @endif
            </div>
        </div>   
        @php
        $divisions = \App\Models\Division::all()
        ->pluck('name', 'id')
        ->toArray();
        @endphp
        <div class="row">
            
                @foreach($epmdata_by_date as $date => $data)
                    @if(!$data['completed']) 
                        <div class="col-md-12">
                    @else
                        <div class="col-md-4">
                    @endif
                    <div class="card">
                        <div class="card-body @if(!$data['completed']) bg-danger 
                            @elseif($data['blocked']) bg-success
                            @endif">
                            <h5 class="card-title">{{ $date }}</h5>
                            <p>
                                Статус: @if($data['completed']) ✅ Заповнено @else ❌ Є порожні @endif
                                @if($data['completed'])  
                                    <a href="{{ route('epmdata.download', ['date' => $date]) }}" class="btn btn-light"
                                    target = "_blank"
                                    >
                                        {{__('Download')}} CSV
                                    </a>
                                @endif
                                

                            </p>

                            <p>Редагування: @if($data['blocked']) 🔒 Закрите @else 
                                @if($data['completed']) 
                                <a href="{{route('epmdata.bloked',['date'=>$date])}}" class="btn btn-light">
                                    🔓 Відкрите                                
                                </a>
                                @else
                                    🔓 Відкрите                                    
                            @endif
                            @endif
                        </p>
                        </div>
                        @if(!$data['blocked']) 
                        <ul class="list-group list-group-flush">
                            @foreach($data['divisions'] as $division_id => $div)
                                <li class="list-group-item @if($div['empty'] > 0) bg-warning @endif">
                                    <strong> {{ $division_id == 'no_division' ? 'Без підрозділу' : $divisions[$division_id] }}:</strong> 
                                    Заповнено {{ $div['total'] - $div['empty'] }} / {{ $div['total'] }} 
                                    (Порожніх: {{ $div['empty'] }})
                                    <a href="{{ route('epmdata.load', ['date' => $date, 'division' => $division_id]) }}" class="btn btn-light">
                                    @if($div['empty'] > 0)    Заповнити @else    Переглянути @endif
                                    </a>
                               
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    </div>
                @endforeach
           
        </div>
    </div>
@endsection
