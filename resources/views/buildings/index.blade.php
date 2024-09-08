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
            <h1>{{__('Building')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin'))
           
                <a class="text-right" href="{{ route('buildings.create') }}">{{__('Create')}}</a>
             @endif</div>
        </div>   
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('ABV')}}</th>
                            <th>{{__('Slug')}}</th>
                            <th>{{__('Organization')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buildings as $building)
                            <tr>
                                <td>{{ $building->name }}</td>
                                <td>{{ $building->abv }}</td>
                                <td>{{ $building->slug }}</td>
                                <td>{{ $building->organization }}</td>
                                <td>{{ $building->status }}</td>
                                <td>
                                    <a href="{{ route('buildings.edit', $building->id) }}">{{__('Edit')}}</a>
                                    <a href="{{ route('buildings.show', $building->id) }}">{{__('Show')}}</a>
                                    <a href="{{ route('buildings.destroy', $building->id) }}">{{__('Delete')}}</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

    </div>
@endsection