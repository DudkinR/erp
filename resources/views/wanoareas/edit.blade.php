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
            <h1>{{__('wanoarea')}}</h1>
                <form method="POST" action="{{ route('wanoarea.update',$wanoarea) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <textarea class="form-control" id="name" name="name" placeholder="{{__('Enter name')}}">{{$wanoarea->name}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" placeholder="{{__('Enter description')}}">{{$wanoarea->description}}</textarea>
                    </div>
                    <div class="form-group">  
                        <label for="abv">{{__('abv')}}</label>
                        <input type="text" class="form-control" id="abv" name="abv" placeholder="{{__('Enter abv')}}" value="{{$wanoarea->abv}}">
                    </div>
                    <div class="form-group">  
                        <label for="focus">{{__('focus')}}</label>
                        <input type="text" class="form-control" id="focus" name="focus" placeholder="{{__('Enter focus')}}" value="{{$wanoarea->focus}}">
                    </div>
                   
                    <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection