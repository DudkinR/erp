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
            <h1>{{__('Events')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('events.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
         <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Start Date of event')}}</th>
                            <th>{{__('End Date of event')}}</th>
                            <th> %</th>
                            <th>{{__('Control Date of event')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                            <tr>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->description }}</td>
                                <td>{{ Carbon\Carbon::parse($event->start_date)->format('d-m-Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($event->end_date)->format('d-m-Y') }}</td>
                                <td>{{ $event->status }}</td>
                                <td>{{ Carbon\Carbon::parse($event->control_date)->format('d-m-Y') }}</td>
                                <td>
                                    <a class="btn btn-light w-100" href="{{ route('events.edit', $event->id) }}">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('events.destroy', $event->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100">{{__('Delete')}}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
@endsection