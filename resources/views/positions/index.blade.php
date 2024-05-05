@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Positions')}}</h1>
                <a class="text-right" href="{{ route('positions.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Start')}}</th>
                            <th>{{__('Data Start')}}</th>
                            <th>{{__('Closed')}}</th>
                            <th>{{__('Data Closed')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($positions as $position)
                            <tr>
                                <td>{{ $position->name }}</td>
                                <td>{{ $position->description }}</td>
                                <td>{{ $position->start }}</td>
                                <td>{{ $position->data_start }}</td>
                                <td>{{ $position->closed }}</td>
                                <td>{{ $position->data_closed }}</td>
                                <td>
                                    <a href="{{ route('positions.edit', $position->id) }}">{{__('Edit')}}</a>
                                    <a href="{{ route('positions.show', $position->id) }}">{{__('Show')}}</a>
                                    <form method="POST" action="{{ route('positions.destroy', $position->id) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit">{{__('Delete')}}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
               
    </div>
@endsection