@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
               
                <a class="text-right" href="{{ route('master.index') }}">{{__('Back')}}</a>
                
                <h2>{{__('Briafing')}}</h2>
                 @foreach ($master->personals as $personal)
                <!-- Код для случая, если брифинг существует у персонала -->
                <div class="row">
                    <div class="col-md-6">
                        <h3> {{ $personal->fio }}</h3>
                    </div>
                    <div class="col-md-6">
                         <h3>{{__('Brief given')}}</h3>
                        </div>
                    </div>
            @endforeach
            <hr>
                <h3>{{__('Task')}}: {{ $master->text }}</h3>
                @php $color = $master->urgency > 5 ? 'red' : ($master->urgency > 3 ? 'orange' : 'green') @endphp
                <h3>{{__('Urgency')}}: <span style="color: {{ $color }}">{{ $master->deadline }}</span></h3>
                <h3>{{__('Basis')}}: {{ $master->basis }}</h3>
                <h3>{{__('Who give task')}}: {{ $master->who }}</h3>
                <h3>{{__('Comment')}}: {{ $master->comment }}</h3>
                <hr>
                <h3>{{__('Docs')}}</h3> <ul>
                @foreach($master->docs as $doc)
               <li>{{$doc->name}}</li>
                @endforeach
            </ul>
             <hr>
             <h3 class="text-danger">{{ __('Spent time') }}: 
                @if($master->start && $master->end)
                    @php
                        $start = \Carbon\Carbon::parse($master->start);
                        $end = \Carbon\Carbon::parse($master->end);
            
                        // Рассчитываем разницу
                        $duration = $end->diff($start);
                        $hours = $duration->h;
                        $minutes = $duration->i;
            
                        // Форматируем разницу
                        $formattedDuration = sprintf('%02d:%02d', $hours, $minutes);
                    @endphp
                    {{ $formattedDuration }}
                @else
                    {{ __('Not Available') }}
                @endif
            </h3>
            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h3> {{ __('Mistakes')}}</h3>
                    <div class="form-group">
                        <textarea class="form-control" rows=10 ></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                     <h3>{{__('Good practices')}}</h3>
                     <div class="form-group">
                        <textarea class="form-control"  rows=10></textarea>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-success w-100">{{__('Go')}}</button>
                </div>
            </div>
      
                    
            </div>
        </div>
    </div>
    <script>
    </script>
@endsection