@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Criteria')}}</h1>
                <a class="text-right" href="{{ route('criteria.create') }}">{{__('Create')}}</a>
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
                                <th>{{__('Weight')}}</th>
                                <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criteries as $criterion)
                            <tr>
                                <td>{{$criterion->id}}</td>
                                <td>{{$criterion->name}}</td>
                                <td>{{$criterion->description}}</td>
                                <td>{{$criterion->weight}}</td>
                                <td>
                                    <a href="{{ route('criteria.edit', $criterion->id) }}">{{__('Edit')}}</a>
                                    <a href="{{ route('criteria.show', $criterion->id) }}">{{__('Show')}}</a>
                                    <form method="POST" action="{{ route('criteria.destroy', $criterion->id) }}">
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