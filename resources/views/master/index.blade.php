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
            <div class="col-md-1">
                {{__('Termin')}}
            </div>
            <div class="col-md-2">
                {{__('Start')}}
            </div>
            <div class="col-md-2">
                {{__('Finish')}}
            </div>
            <div class="col-md-2">
                {{__('Action')}}
            </div>

        </div>  
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>       
        @foreach ($masters as $item)
        @php
        if ($item->estimate == null || $item->personals->count() == 0 || $item->docs->count() == 0 ) {
            $color_class = 'bg-warning';
            $butons = [
                'Edit' => ['btn-warning', route('master.edit', $item->id)],
                'Analize' => ['btn-success',route('master.step1', $item->id)],  
            ];
        } elseif($item->start == null || $item->finish == null &&($item->estimate !== null && $item->personals->count() > 0  )) {
            $color_class = 'bg-info';      
            $butons = [
                'Edit' => ['btn-warning', route('master.edit', $item->id)],
                'Start' => ['btn-success', route('master.step3', $item->id)],
            ];  
        }
  // author_id', 'text', 'basis', 'who', 'urgency', 'deadline', 'estimate', 'start', 'end', 'done', 'comment', 'created_at', 'updated_at'
        // если дедлайн меньше текущей даты и  задача не выполнена
        elseif( $item->deadline < date('Y-m-d') && $item->done == 0)
        {
            $color_class = 'bg-danger';
            $butons = [
                'Edit' => ['btn-warning', route('master.edit', $item->id)],
                'Start' => ['btn-success', route('master.step3', $item->id)],
            ];
        }
         else
        {
            $color_class = 'bg-light';
            $butons = [
                'Edit' => ['btn-warning', route('master.edit', $item->id)],
                'Start' =>  ['btn-success', route('master.step3', $item->id)],
            ];
        }
        
        @endphp



        <div class="row {{$color_class}}">   
            <div class="col-md-1">{{$item->id}}</div>
            <div class="col-md-3">
                <a href="">
                {{ $item->text }}</a>
            </div>
            <div class="col-md-1">
                {{  $item->deadline }}
            </div>
            <div class="col-md-2">{{ $item->start }}</div>
            <div class="col-md-2">{{ $item->finish }}</div>
            <div class="col-md-2">
                @foreach ($butons as $key => $value)
                    <a href="{{ $value[1] }}" class="btn {{ $value[0] }}">{{__($key) }}</a>
                @endforeach
            </div>

        </div>
            
        @endforeach 
          
        <div class="row">
            <a class="text-right" href="{{ route('master.create') }}">{{__('New')}}</a>
        </div>
    </div>
@endsection