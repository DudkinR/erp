@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Position')}}: {{$position->name}}</h1>
                <a class="text-right
                " href="{{ route('positions.index') }}">
                    <button class="btn btn-primary">{{__('Back')}}</button>
                </a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{__('Position')}}</div>
                    <div class="card-body">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>{{__('ID')}}</th>
                                    <td>{{$position->id}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <td>{{$position->name}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Description')}}</th>
                                    <td>{{$position->description}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Start')}}</th>
                                    <td>{{$position->start}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Data Start')}}</th>
                                    <td>{{$position->data_start}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Closed')}}</th>
                                    <td>{{$position->closed}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Data Closed')}}</th>
                                    <td>{{$position->data_closed}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('Divisions')}}</th>
                                    <td>
                                    <ul>
                                        @foreach($position->divisions as $division)
                                            <li>{{$division->name}}</li>
                                        @endforeach
                                    </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
               </div>
            </div>
        </div>
   </div>
@endsection