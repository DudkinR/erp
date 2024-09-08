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
                    {{__('Fact')}}
                </h1>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h1> {{$fact->name}} </h1>
                                @if(Auth::user()->hasRole('quality-engineer','admin'))
                                <a href="{{ route('facts.edit',  $fact) }}" class="btn btn-warning">{{__('Edit')}}</a>
                                <form method="POST" action="{{ route('facts.destroy', $fact) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                                </form>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset('images/'.$fact->image) }}" alt="{{ $fact->name }}" 
                                class="img-fluid float-right w-100" 
                                >
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{__('Created on')}}: {{ date('Y-m-d', strtotime($fact->created_at)) }}</p>
                        <p>{{ $fact->description }}</p>

                    </div>
                    <div class="card-footer">
                        @foreach ($fact->criterias as $criteria)
                        <div class="row">
                            <div class="col-md-3">
                                {{ $criteria->name }}
                            </div>
                            <div class="col-md-9">
                                {{ $criteria->description }}
                            </div>
                        </div>
                        @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
