@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Goals')}}</h1>
                <a class="text-right" href="{{ route('goals.create') }}">{{__('Create Goal')}}</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Title')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($goals as $goal)
                            <tr>
                                <td>{{ $goal->due_date }}</td>
                                <td class="@if($goal->status == '0') text-muted 
                                    @elseif($goal->status == '1') text-primary
                                    @elseif($goal->status == '2') text-success                                    
                                     @endif">{{ $goal->name }} <hr>
                                     @if($goal->funs->count()  > 0)
                                        <h4>Functions</h4>
                                        <ul>
                                            @foreach($goal->funs as $fun)
                                                <li>{{ $fun->name }}</li>
                                            @endforeach
                                        </ul>   
                                    @endif
                                        Add Function: <a href="{{ route('funs.create', ['gl' => $goal->id]) }}">Add</a>
                                    </td>
                                <td>
                                    <a href="{{ route('goals.show', $goal->id) }}" class="btn btn-default">View</a>
                                    <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-warning">Edit</a>
                                    <form style="display:inline-block" method="POST" action="{{ route('goals.destroy', $goal->id) }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="form-control btn btn-danger">Delete</button>
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