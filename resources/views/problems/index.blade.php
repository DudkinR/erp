@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Problems')}}</h1>
                <a class="text-right" href="{{ route('problems.create') }}">{{__('Create')}}</a>
            </div>
        </div>  
      @foreach($problems as $problem)
        <div class="row">
            <div class="col-md-12">
                <h2><a href="{{ route('problems.show', $problem) }}">{{ $problem->name }}</a></h2>
                <p>{{ $problem->description }}</p>
                <a href="{{ route('problems.edit', $problem) }}">{{__('Edit')}}</a>
                <form action="{{ route('problems.destroy', $problem) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">{{__('Delete')}}</button>
                </form>
            </div>
        </div> 
        @endforeach
    </div>
@endsection