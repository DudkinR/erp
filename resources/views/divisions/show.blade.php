@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h1>{{ __('Division') }}: {{ $division->abv }}</h1>
            <a class="btn btn-secondary" href="{{ route('divisions.index') }}">{{ __('Back to Divisions') }}</a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h2>{{ $division->name }}</h2>
                </div>
                <div class="card-body">
                    <p>{{ $division->description }}</p>
                    <p class="text-muted">{{ $division->slug }}</p>

                    @if($division->parent)
                    <p><strong>{{ __('Parent') }}:</strong> {{ $division->parent->name }}</p>
                    @endif

                    <p><strong>{{ __('Number of Personnel') }}:</strong> {{ $count_personal }}</p>

                    @if($division->positions->count() > 0)
                    <p><strong>{{ __('Positions') }}:</strong></p>
                    <ul>
                        @foreach($division->positions as $position)
                        <li>{{ $position->name }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if($under_divisions->count() > 0)
                    <p><strong>{{ __('Subdivisions') }}:</strong></p>
                    <ul>
                        @foreach($under_divisions as $under_division)
                        <li>
                            <a href="{{ route('divisions.show', $under_division->id) }}">
                              
                            {{ $under_division->name }}
                            </a>  - {{ $under_division->personals->count() }} {{ __('personnel') }} 
                        </li>
                        @endforeach
                    </ul>
                    @endif

                    @if(count($rooms) > 0)
                    <p><strong>{{ __('Rooms') }}:</strong></p>
                    <ul>
                        @foreach($rooms as $room)
                        <li>{{ $room->name }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if(count($buildings) > 0)
                    <p><strong>{{ __('Buildings') }}:</strong></p>
                    <ul>
                        @foreach($buildings as $building)
                        <li>{{ $building->name }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @if(Auth::user()->hasRole('quality-engineer','admin'))
                <div class="card-footer text-right">
                    <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST" class="d-inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
