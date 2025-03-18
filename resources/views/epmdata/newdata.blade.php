@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
            <h1>
                @if(!$division)
                    {{__('EPM')}}
                @else
                     {{$division->name}}
                @endif
            </h1>
            <h2>
                {{__('Data for')}} : {{ $date }}
            </h2>
            <a href="{{ route('epmdata') }}" class="btn btn-primary mb-4 w-100"> {{__('Back to Data')}} </a>
            <form method="POST" action="{{ route('epmdata.loadupdate',$date) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <input type="hidden" name="date" value="{{ $date }}">
                    @foreach($epmdatas as $epmdata)
    <div class="form-group border p-2 
        @if($epmdata->value == null) bg-dark text-white 
        @else bg-light 
        @endif">
        
        <label for="value_{{$epmdata->epm_id}}">
            {{ $epms[$epmdata->epm_id]->name }}
        </label>
        
        <input type="text" class="form-control" id="value_{{$epmdata->epm_id}}"
            name="value[{{ $epmdata->id }}]"
            value="{{ old('value.' . $epmdata->id, $epmdata->value) }}"
            placeholder="{{__('Enter value')}}">

        <h6 class="text-muted">
            Min: <b>{{ $epms[$epmdata->epm_id]->min }}</b>  
            & Max: <b>{{ $epms[$epmdata->epm_id]->max }}</b>
            <br>
            
            <!-- Обрізаний текст -->
            <span id="short_{{ $epmdata->epm_id }}">
                {{ Str::limit($epms[$epmdata->epm_id]->description, 50) }}
            </span>

            <!-- Кнопка "Детальніше" -->
            <a href="#" class="text-primary" data-bs-toggle="collapse" data-bs-target="#desc_{{ $epmdata->epm_id }}">
                {{ __('Детальніше') }}
            </a>

            <!-- Розкриваємий текст -->
            <div id="desc_{{ $epmdata->epm_id }}" class="collapse mt-2">
                {!! nl2br(e($epms[$epmdata->epm_id]->description)) !!}
            </div>
        </h6>
    </div>
@endforeach

                   
                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection