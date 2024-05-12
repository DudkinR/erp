@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('nomenclature')}}</h1>
                <a class="text-right" href="{{ route('nomenclaturs.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{__('Nomenclature')}}
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">{{__('Name')}}</label>
                            <h1> {{$nomenclature->name}}</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="description">{{__('Description')}}</label>
                                    <p> {{$nomenclature->description}}</p>  
                                </div>
                                <div class="col-md-6">
                                    @if($nomenclature->image)
                                    <label for="image">{{__('Image')}}</label>
                                    <img src="{{asset('storage/nomenclature/'.$nomenclature->image)}}" alt="image" class="img-thumbnail">
                                    @endif
                                    <a href="{{route('nomenclatures.img.create', $nomenclature->id)}}" class="btn" >{{__('Add Img')}}</a>
                            </div>
                            
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="form-group">
                            <label for="type">{{__('Type')}}</label>
                            <p> {{$nomenclature->type->name}}</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="type">{{__('Docs')}}</label>
                            <ul>
                                
                            </ul>
                            <a href="{{route('nomenclatures.docs.create', $nomenclature->id)}}">Add Docs</a>
                           
                        </div>
                    </div>
            </div>
        </div>
   </div>
@endsection