@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Division')}}:
            {{ $division->abv}}
            </h1>
                <a class="text-right
                " href="{{ route('divisions.index') }}">{{__('Divisions')}}</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <h1>
                        {{ $division->name }}
                    </h1>
                    <p>
                        {{ $division->description }}
                    </p>
                    <p class="text-right">
                        {{ $division->slug }}
                    </p>
                    @if($division->parent)
                        <p>
                            {{__('Parent')}}:
                            {{ $division->parent->name }}
                        </p>
                    @endif
                    @if($division->positions->count() > 0)
                        <p>
                            {{__('Positions')}}:
                            <ul>
                                @foreach($division->positions as $position)
                                    <li>{{ $position->name }}</li>
                                @endforeach
                            </ul>
                        </p>
                    @endif
               </div>
               <div class="card-footer">
                    <a href="{{ route('divisions.edit', $division->id) }}">{{__('Edit')}}</a>
                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">{{__('Delete')}}</button>
                    </form>

               </div>
            </div>
        </div>
   </div>
@endsection