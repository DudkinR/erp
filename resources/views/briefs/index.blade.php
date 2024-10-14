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
            <h1>{{__('Briefs')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('briefs.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-1">                
                {{__('Number')}}
            </div>
            <div class="col-md-6">                
                {{__('Name')}}            
            </div>
            <div class="col-md-1">
                {{__('Type')}}
            </div>
            <div class="col-md-1">
                {{__('Risk')}}
            </div>
            <div class="col-md-1">
                {{__('Functionality')}}
            </div>  
            <div class="col-md-2">
                {{__('Drive')}}
            </div>
        </div>   
        @foreach($briefs as $brief)
        <div class="row">
            <div class="col-md-1">
                {{$brief->order}}
            </div>
            <div class="col-md-6" @if($brief->name_uk=='') style="background-color: yellow;" @endif>                
                @if($brief->name_uk!=''){{$brief->name_uk}}
                @elseif($brief->name_en!='' && $brief->name_uk==''){{$brief->name_en}}
                @else{{ str_replace('&nbsp;', ' ', strip_tags($brief->name_ru))}}
                @endif
            </div>
            <div class="col-md-1">
                {{$brief->type}}
            </div>
            <div class="col-md-1">
                {{$brief->risk}}
            </div>
            <div class="col-md-1">
                {{$brief->functional}}
            </div>
            <div class="col-md-2">

                <a class="btn btn-light w-100" href="{{ route('briefs.edit', $brief->id) }}">{{__('Edit')}}</a>
           
            </div>
        </div>
        @endforeach
    </div>
@endsection