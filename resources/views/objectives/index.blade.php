@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Objectives')}}</h1>
            @if(Auth::user()->hasRole('admin'))
                <a class="btn btn-info w-100" href="{{ route('objectives.create') }}">{{__('Create')}}</a>
            @endif
            </div>
        </div>  
        @foreach ($objectives as $objective)
            <div class="row">
                <div class="col-md-12">
                    <h2>{{ $objective->name }}</h2>
                    <p>{{ $objective->description }}</p>
                    <p>{{__('Goals')}}: 
                       {{$objective->goals->count()}}
                    </p>
                    <p>{{__('Functs')}}: 
                       {{$objective->functs->count()}}
                    </p>
                    <a href="{{ route('objectives.show', $objective) }}"
                          class="btn btn-primary"
                    >{{__('Show')}}</a>
                    @if(Auth::user()->hasRole('admin'))
                    <a href="{{ route('objectives.edit', $objective) }}"
                            class="btn btn-warning"
                    >{{__('Edit')}}</a>
                    <form method="POST" action="{{ route('objectives.destroy', $objective) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn btn-danger" type="submit">{{__('Delete')}}</button>
                    </form>
                    @endif
                </div>
            </div>
        @endforeach  
    </div>
@endsection