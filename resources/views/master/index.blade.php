@extends('layouts.app')
@section('content')
<div class="container mt-4 p-3 border rounded bg-light shadow-sm">
    <div class="row mb-3">
        <div class="col-md-12 text-center">
            <h1 class="text-primary">{{ __('Master Tasks') }}</h1>
        </div>
    </div>
    @if(Auth::user()->hasRole('quality-engineer','admin'))
    <div class="row mb-4">
        <div class="col-md-12">
            <a class="btn btn-success w-100 btn-lg mb-3" href="{{ route('master.create') }}">
                <i class="fas fa-plus"></i> {{ __('New Task') }}
            </a>
        </div>
    </div>
    @endif

    <!-- Table Headers -->
    <div class="row fw-bold border-bottom pb-2 mb-3">
        <div class="col-md-1">{{ __('№') }}</div>
        <div class="col-md-4">{{ __('Task') }}</div>
        <div class="col-md-2">{{ __('Deadline') }}</div>
        <div class="col-md-2">{{ __('Start') }}</div>
        <div class="col-md-2">{{ __('Finish') }}</div>
        <div class="col-md-1">{{ __('Action') }}</div>
    </div>
    

    <!-- Display each task -->
    @foreach ($masters as $item)
        @php
            // Determining the visual style and actions based on the task status
            $color_class = 'bg-light';
            $buttons = [];
            $urgency_icon = '';
           if(Auth::user()->hasRole('quality-engineer','admin'))////////////////////////////////////////////////////////////////////////////////////////
            {
            if ($item->personals->count() == 0 && $item->docs->count() == 0) {
                $color_class = 'bg-warning';
                $buttons = ['Analyze' => ['btn-success', route('master.step1', $item->id)]];
                $current_step = __('Task Creation');
                $urgency_icon = '<i class="fas fa-exclamation-circle text-warning"></i>';
            } elseif (is_null($item->start)) {
                $color_class = 'bg-info';
                $buttons = ['Start' => ['btn-success', route('master.step3', $item->id)]];
                $current_step = __('Task Analysis');
                $urgency_icon = '<i class="fas fa-play-circle text-info"></i>';
            } elseif ($item->deadline < date('Y-m-d') && $item->done == 0 && is_null($item->start)) {
                $color_class = 'bg-danger';
                $buttons = ['End' => ['btn-success', route('master.step3', $item->id)]];
                $current_step = __('Briefing and Start');
                $urgency_icon = '<i class="fas fa-times-circle text-danger"></i>';
            } elseif ($item->deadline < date('Y-m-d') && $item->done == 0 && !is_null($item->start)) {
                $color_class = 'bg-danger';
                $buttons = ['End' => ['btn-primary', route('master.step5', $item->id)]];
                $current_step = __('Briefing and Start');
                $urgency_icon = '<i class="fas fa-exclamation-triangle text-danger"></i>';
            } elseif ($item->done == 0 && !is_null($item->start) && is_null($item->finish)) {
                $color_class = 'bg-success';
                $buttons = ['End' => ['btn-primary', route('master.step5', $item->id)]];
                $current_step = __('Completion or Reschedule');
                $urgency_icon = '<i class="fas fa-check-circle text-success"></i>';
            } else {
                $color_class = 'bg-light';
                $buttons = ['Analyze Completion' => ['btn-danger', route('master.step4', $item->id)]];
                $current_step = __('Completion Analysis');
            }
        }
        
        @endphp
<div class="row {{ $color_class }} p-2 rounded mb-2 shadow-sm align-items-center">
    <div class="col-md-1">{{ $item->id }}</div>
    <div class="col-md-4">
        {!! $urgency_icon !!} {{ $item->text }}
    </div>
    <div class="col-md-2">
        {{ \Carbon\Carbon::parse($item->deadline)->format('Y-m-d') }}
    </div>
    <div class="col-md-2">
        @if($item->start)
            <a title="{{ \Carbon\Carbon::parse($item->start)->format('Y-m-d') }}">
                {{ \Carbon\Carbon::parse($item->start)->format('H:i') }}
            </a>
        @else
            {{ __('Not Set') }}
        @endif
    </div>
    <div class="col-md-2">
        @if($item->finish)
            <a title="{{ \Carbon\Carbon::parse($item->finish)->format('Y-m-d') }}">
                {{ \Carbon\Carbon::parse($item->finish)->format('H:i') }}
            </a>
        @else
            {{ __('Not Set') }}
        @endif
    </div>
    <div class="col-md-1 d-flex gap-2">
        @foreach ($buttons as $key => $value)
            <a href="{{ $value[1] }}" class="btn {{ $value[0] }} d-flex align-items-center">
                <i class="fas fa-arrow-right me-2"></i> {{ __($key) }}
            </a>
        @endforeach
    </div>
</div>

    @endforeach
    @if(Auth::user()->hasRole('quality-engineer','admin'))
    <!-- Bottom New Task Button -->
    <div class="row mt-4">
        <div class="col-md-12">
            <a class="btn btn-success w-100 btn-lg" href="{{ route('master.create') }}">
                <i class="fas fa-plus"></i> {{ __('New Task') }}
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
