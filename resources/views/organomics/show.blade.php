@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Organomics')}}</h1>               
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
        <a class="btn btn-primary" href="{{ route('organomic.index') }}">{{__('Back')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">                
                {{__('Name')}}            
            </div>
            <div class="col-md-6">
                {{__('Description')}}
            </div>
            <div class="col-md-2">
                {{__('Personal Count')}}
            </div>
            <div class="col-md-2">
                {{__('Effectiveness')}}
            </div>
        </div>
        @php $floor= null; @endphp
        @foreach($effectiveness as $room)
        @if($floor != $room['floor'])
        <div class="row">
            <div class="col-md-12">
                <h2>{{__('Floor')}} {{$room['floor']}}</h2>
            </div>
        </div>
        @php $floor = $room['floor']; @endphp
        @endif
        <div class="row  @if($room['personal_count']>0) bg-warning @endif">
            <div class="col-md-2">                
                {{$room['name']}}            
            </div>
            <div class="col-md-6">
                {{$room['description']}}
            </div>
            <div class="col-md-2">
                {{$room['personal_count']}}
            </div>
            <div class="col-md-2
            @if($room['effectiveness']<10&&$room['effectiveness']!==0) bg-danger
            ">
                {{$room['effectiveness']}}
            </div>
        </div>
        @endforeach
    </div>
@endsection