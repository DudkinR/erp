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
            <h1>{{$car->name}}</h1>
                <a class="btn btn-light w-100" href="{{ route('cars.index') }}">
                {{__('Back')}}</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{__('Car')}}
                    </div>
                    <div class="card-body">
                        <p>{{__('Gov Number')}}: {{$car->gov_number}}</p>
                        <p>{{__('Type')}}: {{$car->type->name}}</p>
                        <p>{{__('Condition')}}: {{$car->condition->name}}</p>
                    </div>
               </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{__('Actions')}}
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->hasRole('admin'))
                        <a href="{{ route('cars.edit', $car->id) }}" class="btn btn-warning">{{__('Edit')}}</a>
                        <form action="{{ route('cars.destroy', $car->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit">{{__('Delete')}}</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection