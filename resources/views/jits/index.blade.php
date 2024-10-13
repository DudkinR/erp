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
            <h1>{{__('JITs')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('jits.create') }}">{{__('Create')}}</a>
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
                {{__('Keywords')}}
            </div>  
            <div class="col-md-2">
                {{__('Drive')}}
            </div>
        </div>
        @foreach($jits as $jit)
        <div class="row" 
        @if($jit->description_uk=='') style="background-color: #ff0000;" @endif
        >
            <div class="col-md-2">                
                {{$jit->name_ru}}
                <hr>
                {{$jit->name_uk}}
                <hr>
                {{$jit->name_en}}

            </div>
            <div class="col-md-6">

                {!!$jit->description_ru!!}
                <hr>
                {{$jit->description_uk}}
                <hr>
                {{$jit->description_en}}
            </div>
            <div class="col-md-2">
                {{$jit->keywords}}
            </div>
            
            <div class="col-md-2">
                {{$jit->jitqws->count()}}
                <hr>
                <a class="btn btn-light" href="{{ route('jits.edit', $jit->id) }}">{{__('Edit')}}</a>
          
                <form method="POST" action="{{ route('jits.destroy', $jit->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">{{__('Delete')}}</button>
                </form>
            </div>
        </div>
        <hr style="border-top: 1px solid #000;">
        @endforeach
    </div>
@endsection