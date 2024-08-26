@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Master')}}</h1>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-1">№</div>
            <div class="col-md-3">
                {{__('Task')}}
            </div>
            <div class="col-md-2">
                {{__('Termin')}}
            </div>
            <div class="col-md-1">
                {{__('Start')}}
            </div>
            <div class="col-md-1">
                {{__('Finish')}}
            </div>
            <div class="col-md-4">
                {{__('Action')}}
            </div>

        </div>  
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>       
        @foreach ($masters as $item)
        @php
            // Инициализация переменных
            $color_class = 'bg-light';
            $buttons = [];
    
            // Этап 1: Создание задания// Этап 2: Анализ задания
            if ( $item->personals->count() == 0 && $item->docs->count() == 0) {
                $color_class = 'bg-warning';
                $buttons = [
                  ////  'Edit' => ['btn-warning', route('master.edit', $item->id)],
                    'Analyze' => ['btn-success', route('master.step1', $item->id)],
                ];
                $current_step = "Создание задания";
            } 
            
            elseif (is_null($item->start)) {
                $color_class = 'bg-info';
                $buttons = [
                  //  'Edit' => ['btn-warning', route('master.edit', $item->id)],
                    'Start' => ['btn-success', route('master.step3', $item->id)],
                ];
                $current_step = "Анализ задания";
            }
            // Этап 3: Инструктаж и начало работы
            elseif ($item->deadline < date('Y-m-d') && $item->done == 0 && is_null($item->start)) {
                $color_class = 'bg-danger';
                $buttons = [
                  //  'Edit' => ['btn-warning', route('master.edit', $item->id)],
                    'End' => ['btn-success', route('master.step3', $item->id)],
                ];
                $current_step = "Инструктаж и начало работы";
            }
            elseif ($item->deadline < date('Y-m-d') && $item->done == 0 && !is_null($item->start)) {
                $color_class = 'bg-danger';
                $buttons = [
                  //  'Edit' => ['btn-warning', route('master.edit', $item->id)],
                    'End' => ['btn-primary', route('master.step5', $item->id)],
                ];
                $current_step = "Инструктаж и начало работы";
            }
            // Этап 4: Завершение или перенос
            elseif ($item->done == 0 && !is_null($item->start) && is_null($item->finish)) {
                $color_class = 'bg-success';
                $buttons = [
                    'End' => ['btn-primary', route('master.step5', $item->id)],
                ];
                $current_step = "Завершение работы или перенос";
            }
            // Этап 5: Анализ выполненной работы
            else {
                $color_class = 'bg-light';
                $buttons = [
                  //  'Edit' => ['btn-warning', route('master.edit', $item->id)],
                    'Analyze Completion' => ['btn-danger', route('master.step4', $item->id)],
                ];
                $current_step = "Анализ выполненной работы";
            }
        @endphp
    
        <div class="row {{ $color_class }}">   
            <div class="col-md-1">{{ $item->id }}</div>
            <div class="col-md-3">
                
                    {{ $item->text }}
                
            </div>
            <div class="col-md-2">
                {{ \Carbon\Carbon::parse($item->deadline)->format('Y-m-d') }}
            </div>
            <div class="col-md-1">
                @if($item->start)
                <a title="{{ \Carbon\Carbon::parse($item->start)->format('Y-m-d') }}">
                {{ \Carbon\Carbon::parse($item->start)->format('H:i') }}</a>
                @else
                    <!-- Можно показать что-то другое, например, пустую строку или текст "Не задано" -->
                    {{ __('Not Set') }}
                @endif
             
            </div>
            <div class="col-md-1">
                @if($item->end)
                <a title="{{ \Carbon\Carbon::parse($item->end)->format('Y-m-d') }}">
                {{ \Carbon\Carbon::parse($item->end)->format('H:i') }}</a>
            @else
                <!-- Можно показать что-то другое, например, пустую строку или текст "Не задано" -->
                {{ __('Not Set') }}
            @endif
        </div>
            <div class="col-md-4">
                @foreach ($buttons as $key => $value)
                    <a href="{{ $value[1] }}" class="btn {{ $value[0] }}">{{ __($key) }}</a>
                @endforeach
            </div>
        </div>
    @endforeach
    
          
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>
    </div>
@endsection