@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <div class="row mb-3">
        <div class="col-md-12 text-end">
            <a class="btn btn-outline-secondary btn-sm" href="{{ route('master.index') }}">{{ __('Back') }}</a>
        </div>
    </div>
    
    <h2 class="text-primary mb-4">{{ __('Briefing') }}</h2>
    
    @foreach ($master->personals as $personal)
    <div class="row mb-3">
        <div class="col-md-6">
            <h4>{{ $personal->fio }}</h4>
        </div>
        <div class="col-md-6 text-end">
            <span class="badge bg-success">{{ __('Brief given') }}</span>
        </div>
    </div>
    @endforeach

    <hr>
    
    <h3>{{ __('Task') }}: <span class="text-dark">{{ $master->text }}</span></h3>
    
    @php 
        $urgencyColor = $master->urgency > 5 ? 'danger' : ($master->urgency > 3 ? 'warning' : 'success');
    @endphp
    <h3>{{ __('Urgency') }}: 
        <span class="badge bg-{{ $urgencyColor }}">{{ $master->deadline }}</span>
    </h3>
    <h3>{{ __('Finished') }}: 
        <span class="badge bg-{{ $master->done ? 'success' : 'danger' }}">
            {{ $master->done ? '👍' : '👎' }} {{ $master->end}}
        </span>
    </h3>
    
    <h3>{{ __('Basis') }}: <span class="text-muted">{{ $master->basis }}</span></h3>
    <h3>{{ __('Who gave the task') }}: <span class="text-muted">{{ $master->who }}</span></h3>
    <h3>{{ __('Comment') }}: <span class="text-muted">{{ $master->comment }}</span></h3>
    
    <hr>
    
    <h3>{{ __('Docs') }}</h3>
    <ul class="list-group">
        @foreach($master->docs as $doc)
        <li class="list-group-item">{{ $doc->name }}</li>
        @endforeach
    </ul>
    
    <hr>
    
    <h3 class="text-danger">{{ __('Spent time') }}: 
        @if($master->start && $master->end)
            @php
                $start = \Carbon\Carbon::parse($master->start);
                $end = \Carbon\Carbon::parse($master->end);

                $duration = $end->diff($start);
                $hours = $duration->h;
                $minutes = $duration->i;

                $formattedDuration = sprintf('%02d:%02d', $hours, $minutes);
            @endphp
            {{ $formattedDuration }}
        @else
            {{ __('Not Available') }}
        @endif
    </h3>
    
    <hr>
    <form action="{{ route('master.step5', $master->id) }}" method="post" id="taskForm">
        @csrf
        <input type="hidden" name="mistakes[]" id="mistakes">
        <input type="hidden" name="good_practices[]" id="good_practices">
        <div class="row mb-4">
            <div class="col-md-6">
                <h3>{{ __('Mistakes') }}</h3>
                <div class="form-group" id="show_mistakes"></div>
                <div class="form-group">
                    <textarea class="form-control" id="mst" rows="10" placeholder="{{ __('Describe mistakes here...') }}"></textarea>
                </div>
                <button type="button" class="btn btn-danger w-100" id="add_mistake" onclick="AddM()">{{ __('Add') }}</button>
            </div>
            <div class="col-md-6">
                <h3>{{ __('Good practices') }}</h3>
                <div class="form-group" id="show_good_practices"></div>
                <div class="form-group">
                    <textarea class="form-control" id="gpr" rows="10" placeholder="{{ __('Describe good practices here...') }}"></textarea>
                </div>
                <button type="button" class="btn btn-success w-100" id="add_good_practice" onclick="AddGP()">{{ __('Add') }}</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-success w-100" id="submit_btn">{{ __('Go') }}</button>
            </div>
        </div>
    </form>
    
    </div>
    
    <script>
      document.addEventListener('DOMContentLoaded', function() {
    var texts_mistakes = [];
    var texts_good_practices = [];

    function AddM() {
        var mistakeText = document.getElementById('mst').value.trim();
        if (mistakeText) {
            texts_mistakes.push(mistakeText);
            document.getElementById('show_mistakes').innerHTML += 
                '<div class="alert alert-danger">' + mistakeText + '</div>';
            document.getElementById('mst').value = ''; // Очистка текстового поля
            // Обновляем скрытое поле с данными
            document.getElementById('mistakes').value = texts_mistakes.join('|');
        }
    }

    function AddGP() {
        var goodPracticeText = document.getElementById('gpr').value.trim();
        if (goodPracticeText) {
            texts_good_practices.push(goodPracticeText);
            document.getElementById('show_good_practices').innerHTML += 
                '<div class="alert alert-success">' + goodPracticeText + '</div>';
            document.getElementById('gpr').value = ''; // Очистка текстового поля
            // Обновляем скрытое поле с данными
            document.getElementById('good_practices').value = texts_good_practices.join('|');
        }
    }
    
    // Обработчики для кнопок
    document.getElementById('add_mistake').addEventListener('click', AddM);
    document.getElementById('add_good_practice').addEventListener('click', AddGP);
});

    </script>
@endsection
