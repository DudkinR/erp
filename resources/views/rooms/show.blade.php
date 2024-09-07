@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Rooms') }}</h1>
            <a class="btn btn-primary" href="{{ route('rooms.index') }}">
                {{ __('Back') }}
            </a>
        </div>
    </div>

    <!-- Building Information -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ $building[0]->name ?? __('Unknown Building Name') }}
                </div>
                <div class="card-body">
                    <p><strong>{{__('Abbreviation')}}:</strong> {{ $building[0]['abv'] ?? 'N/A' }}</p>
                    <p><strong>{{__('Address')}}:</strong> {{ $building[0]['address'] ?? 'N/A' }}</p>
                    <p><strong>{{__('City')}}:</strong> {{ $building[0]['city'] ?? 'N/A' }}</p>
                    <p><strong>{{__('Country')}}:</strong> {{ $building[0]['country'] ?? 'N/A' }}</p>
                    <p><strong>{{__('Postal Code')}}:</strong> {{ $building[0]['postal_code'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms and Personnel Information -->
    <div class="row mt-4">
        <div class="col-md-12">
            @foreach ($building[0]['rooms'] ?? [] as $room)
                <div class="card mb-3">
                    <div class="card-header">
                        <strong> {{ __('Room') }}:</strong> {{ $room['name'] ?? __('Unknown Room Name') }} 
                        
                    </div>
                    <div class="card-body">
                        <p><strong>{{__('Square Meters')}}:</strong> {{ $room['square'] ?? 'N/A' }}</p>

                        <p><strong>
                            {{__('Floor')}}:
                        </strong> {{ $room['floor'] ?? 'N/A' }}</p>
                        <p><strong>{{__('Owner Division')}}:</strong> 
                            @php $division = \App\Models\Division::find($room['owner_division']); @endphp
                            {{ $division->name ?? 'N/A' }}
                        </p>
                        
                        <!-- Display Personnel Information -->
                        @if (!empty($room['personals']))
                        <p>
                            <strong>{{__('Personnels')}}:</strong>
                            {{ count($room['personals']) }}
                        </p>
                           
                        @else
                            <p>No personnel assigned to this room.</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
