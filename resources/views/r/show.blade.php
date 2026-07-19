@extends('layouts.app')

@section('content')
<div class="container my-5">

    <!-- Заголовок та кнопка назад -->
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h1 class="fw-bold text-primary">{{ $risk->name }}</h1>
            <a class="btn btn-outline-secondary" href="{{ route('r.index') }}">
                ⬅ {{ __('Back to List') }}
            </a>
        </div>
    </div>

    <!-- Основна картка ризику -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-secondary">{{ __('Risk Details') }}</h5>
                </div>
                <div class="card-body">
                    <!-- Опис -->
                    <p class="mb-3">
                        <strong>{{ __('Description') }}:</strong><br>
                        {{ $risk->description ?? __('No description provided') }}
                    </p>

                    <!-- КНДК -->
                    <p class="mb-0">
                        <strong>{{ __('KNDK Activities') }}:</strong><br>
                        @if($risk->kndks->isEmpty())
                            <span class="text-muted">{{ __('No KNDK linked') }}</span>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($risk->kndks as $kndk)
                                    <li class="list-group-item">{{ $kndk->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </p>
                </div>
                <div class="card-footer text-end bg-light">
                    <small class="text-muted">{{ __('Created at') }}: {{ $risk->created_at->format('d.m.Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
