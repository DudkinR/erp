@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Function')}}</h1>
                <div class="card">
                    <div class="card-header">
                        {{ $fun->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $fun->description }}</p>
                        
                        <h2>{{__('Positions')}}</h2>
                        <ul>
                            @foreach($fun->positions as $position)
                                <li>{{ $position->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-primary" href="{{ route('funs.edit', $fun) }}">{{__('Edit')}}</a>
            </div>
            <div class="col-md-6">
                <form method="POST" action="{{ route('funs.destroy', $fun) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
