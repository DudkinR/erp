@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Call Details') }}</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Work Description') }}: {{ $calling->description }}</h5>
                    <p><strong>{{ __('Arrival Time') }}:</strong> {{ $calling->arrival_time }}</p>
                    <p><strong>{{ __('Start Time') }}:</strong> {{ $calling->start_time }}</p>
                    <p><strong>{{ __('Work Time') }}:</strong> {{ $calling->work_time }}</p>
                    <p><strong>{{ __('End Time') }}:</strong> {{ $calling->end_time }}</p>
                    <p><strong>{{ __('Workers') }}:</strong></p>
                    <ul>
                        @foreach($calling->workers as $worker)
                            <li>{{ $worker->fio }}</li>
                        @endforeach
                    </ul>
                    <p><strong>{{ __('Chief') }}:</strong> 
                        {{ $calling->chief ? $calling->chief->fio : __('Not assigned') }}
                    </p>
                    <p><strong>{{ __('Payment Types') }}:</strong></p>
                    <ul>
                      
                    </ul>
                    <a href="{{ route('callings.index') }}" class="btn btn-secondary">{{ __('Back to List') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
