@extends('layouts.app')
@section('content')
    <div class="container">
        @if(isset($goal) )
            <div class="row">
                <div class="col-md-12">
                    <h1>{{$goal->name}}</h1>
                    <p>{{$goal->description}}</p>
                    <p>Due Date: {{$goal->due_date}}</p>
                    <p>Status: 
                        @if($goal->status == '0')
                            Not Started
                        @elseif($goal->status == '1')
                            In Progress
                        @elseif($goal->status == '2')
                            Complete
                        @endif  
                    </p>
                    @if($goal->status == '2')
                        <p>Completed On: {{$goal->completed_on}}</p>
                    @endif
                    <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-warning">Edit</a>
                    <form style="display:inline-block" method="POST" action="{{ route('goals.destroy', $goal->id) }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit" class="form-control btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <h1>Functions</h1>
                <a class="text-right" href="{{ route('funs.create') }}">Create Function</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($funs as $funct)
                            <tr>
                                <td >{{ $funct->name }}</td>
                                
                                <td>{{ $funct->description }}</td>
                                <td>
                                    <a href="{{ route('funs.show', $funct->id) }}" class="btn btn-default">View</a>
                                    <a href="{{ route('funs.edit', $funct->id) }}" class="btn btn-warning">Edit</a>
                                    <form style="display:inline-block" method="POST" action="{{ route('funs.destroy', $funct->id) }}">
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