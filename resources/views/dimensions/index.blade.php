@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Dimension')}}</h1>
                <a class="text-right" href="{{ route('dimensions.create') }}">{{__('Create new')}}</a>
            </div>
            @foreach($dimensions as $dimension)
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a href="{{ route('dimensions.show',$dimension) }}">{{ $dimension->name }}</a>
                        </div>
                        <div class="card-body">
                            <p>{{ $dimension->description }}</p>
                            <a href="{{ route('dimensions.edit',$dimension) }}">{{__('Edit')}}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>    
    </div>
@endsection