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
            <h1>{{__('EPM')}}</h1>
            @if(Auth::user()->hasRole('admin'))    
                <a class="btn btn-light w-100" href="{{ route('epm.create') }}">{{__('Create')}}</a>
                @endif
            </div>
        </div>   
        @php 
            $areas = \App\Models\WANOAREA::all(); 
        @endphp
        @foreach($epms as $epm)
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $epm->name }}
                        -
                        {{ $areas->where('id', $epm->area)->first()->name }}
                   
                    </div>
                    <div class="card-body">
                        <p>{{ $epm->description }}</p>
                        @if(Auth::user()->hasRole('admin'))    
                        <a class="btn btn-light" href="{{ route('epm.edit', $epm->id) }}">{{__('Edit')}}</a>
                        <form method="POST" action="{{ route('epm.destroy', $epm->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach 
    </div>
@endsection