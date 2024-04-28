@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Steps')}}</h1>
                <a class="text-right" href="{{ route('steps.create') }}">{{__('Create')}}</a>
            </div>
        </div>
        @foreach($steps as $step)
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('steps.show', $step->id) }}">{{ $step->name }}</a>
                        </div>
                        <div class="card-body">
                            <p>{{ $step->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach    
    </div>
@endsection