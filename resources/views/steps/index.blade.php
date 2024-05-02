@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Steps')}}</h1>
                <a class="text-right" href="{{ route('steps.create') }}">{{__('New')}}</a>
            </div>
        </div> 
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($steps as $step)
                        <tr>
                            <td>{{ $step->id }}</td>
                            <td>{{ $step->name }}</td>
                            <td>{!! nl2br(e($step->description)) !!}</td>
                            <td>
                                <a href="{{ route('steps.edit',$step) }}" class="btn btn-primary">{{__('Edit')}}</a>
                                <a href="{{ route('steps.show',$step) }}" class="btn btn-success">{{__('Show')}}</a>
                                <form method="POST" action="{{ route('steps.destroy',$step) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                   
    </div>
@endsection