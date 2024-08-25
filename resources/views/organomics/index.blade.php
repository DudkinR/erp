@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Organomics')}}</h1>
                <a class="text-right" href="{{ route('organomic.create') }}">{{__('Add')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Efectivness')}}</th>
                     
                            <th>{{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buildings as $building)
                            <tr>
                                <td>{{ $building->name }}</td>
                                <td>{{ $building->effectivness }}</td>
                
                                <td>
                                
                                    <a href="{{ route('organomic.show', $building->id) }}">{{__('Show')}}</a>
                                   
                                </td>
                            </tr>   
                          
                        @endforeach
                    </tbody>
                </table>
            </div>
        
    </div>
@endsection