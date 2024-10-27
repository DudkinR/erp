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
            <h1>{{__('Carorders')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('carorders.create') }}">{{__('Create')}}</a>
            </div>
        </div>
        @foreach($carorders as $carorder)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('carorders.show', $carorder->id) }}">{{ $carorder->title }}</a>
                            ({{ $all_types[$carorder->typecar_id]->name }}) 
                            {{$carorder->typecar_id}}
                        </div>
                        <div class="card-body">
                            <p>{{ $carorder->description }}</p>
                        </div>
                    </div>
                </div>
            </div>    
        @endforeach
    </div>
@endsection