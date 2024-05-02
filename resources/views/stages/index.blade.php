@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Stages')}}</h1>
                <a class="text-right" href="{{ route('stages.create') }}">{{__('Stage')}}</a>
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
                        @foreach($stages as $stage)
                        <tr>
                            <td>{{ $stage->id }}</td>
                            <td>{{ $stage->name }}</td>
                            <td>{!! nl2br(e($stage->description)) !!}</td>

                            <td>
                                <a href="{{ route('stages.edit',$stage) }}" class="btn btn-primary">{{__('Edit')}}</a>
                                <a href="{{ route('stages.show',$stage) }}" class="btn btn-success">{{__('Show')}}</a>
                                <form method="POST" action="{{ route('stages.destroy',$stage) }}">
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
    </div>
@endsection