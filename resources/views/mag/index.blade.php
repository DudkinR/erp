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
            <h1>{{__('Magazines')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin','department-chief'))
                <a class="btn btn-light text-right" href="{{ route('mag.create') }}">{{__('Create')}}</a>
            @endif
            </div>
        </div>    
        <div class="row">
            @foreach($magtables as $mag)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{ $mag->name }}</h2>
                            <a href="{{route('mag.show',$mag)}}" class="btn btn-primary">{{__('View')}}</a>
                        </div>
                        <div class="card-body">
                            <p>{{ $mag->description }}</p>
                            <p>{{__('Published on')}}: {{ $mag->created_at }}</p>
                       @if(Auth::user()->hasRole('quality-engineer','admin'))
                                <a href="{{ route('mag.edit', $mag) }}" class="btn btn-primary">{{__('Edit')}}</a>
                                <form action="{{ route('mag.destroy', $mag) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">{{__('Delete')}}</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
    </div>
@endsection