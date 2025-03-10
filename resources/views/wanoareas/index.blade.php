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
                <a class="btn btn-light w-100" href="{{ route('wanoarea.create') }}">{{__('Create')}}</a>
            </div>
        </div>  
        @foreach($wanoareas as $wanoarea)
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $wanoarea->name }}
                    </div>
                    <div class="card-body">
                        <small>{{ $wanoarea->abv }}</small> <small>{{ $wanoarea->focus }}</small><p>{{ $wanoarea->description }}</p>
                        <a class="btn btn-light" href="{{ route('wanoarea.edit', $wanoarea->id) }}">{{__('Edit')}}</a>
                        <form method="POST" action="{{ route('wanoarea.destroy', $wanoarea->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach 
    </div>
@endsection