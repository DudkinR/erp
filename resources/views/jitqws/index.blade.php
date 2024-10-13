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
            <h1>{{__('Questions JITs')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('jitqws.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
        <div class="row">
            <div class="col-md-3">                
                {{__('Description UK')}}            
            </div>
            <div class="col-md-3">
                {{__('Description EN')}}
            </div>
            <div class="col-md-3">
                {{__('Description Ru')}}
            </div>  
            <div class="col-md-3">
                {{__('Drive')}}
            </div>
        </div>
        @foreach($jitqws as $jitqw)
        <div class="row" 
        @if($jitqw->description_uk=='') style="background-color: #ff0000;" @endif
        >
            <div class="col-md-3">                
                {{$jitqw->description_uk}}
            </div>
            <div class="col-md-3">
                {{$jitqw->description_en}}
            </div>
            <div class="col-md-3">
                {{$jitqw->description_ru}}
            </div>
            <div class="col-md-3">
                {{$jitqw->jits->count()}} {{__('Used JITs')}}  
                <hr>
                {{$jitqw->briefs->count()}} {{__('Used briefs')}}
                <hr>
                <a class="btn btn-light w-100" href="{{ route('jitqws.edit', $jitqw->id) }}">{{__('Edit')}}</a>
                <form method="POST" action="{{ route('jitqws.destroy', $jitqw->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light w-100">{{__('Delete')}}</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
@endsection