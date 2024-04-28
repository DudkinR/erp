@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Types')}}</h1>
                <a class="text-right" href="{{ route('types.create') }}">{{__('Create')}}</a>
            </div>
        </div>  
        @foreach ($types as $type)
            <div class="row" style="background-color: {{ $type->color }}">
                <div class="col-md-3">
                    <h2>{{ $type->name }}</h2>
                </div>
                <div class="col-md-4">
                    <p>{{ $type->description }}</p>
                                  </div>
                <div class="col-md-1">
                    <p>{{ $type->slug }}</p>
                </div>
                <div class="col-md-2">
                    @if(is_file (public_path('storage/types/'.$type->icon)))    
                        <img src="{{ asset('storage/types/'.$type->icon) }}" alt="{{ $type->name }}" style="width: 100px; height: 100px;">
                    @else
                        img
                    @endif
                </div>
                <div class="col-md-2"> 
                    <a href="{{ route('types.edit', $type->id) }}">{{__('Edit')}}</a>
                    <a href="{{ route('types.show', $type->id) }}">{{__('Show')}}</a>
                    <form method="POST" action="{{ route('types.destroy', $type->id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit">{{__('Delete')}}</button>
                    </form>

                </div>
            </div>
        @endforeach  
    </div>
@endsection