@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Goals')}}</h1>
                @if(Auth::user()->hasRole('quality-engineer','admin'))
                <a class="text-right" href="{{ route('goals.create') }}">{{__('Create Goal')}}</a>
                @endif
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
                                    @elseif($goal->status == '3') text-danger
                                    @endif
                                    border 
                                     ">{{ $goal->name }} <hr>
                                     <h4>
                                        {{__('Objectives')}}:
                                     </h4>
                                        <ul>
                                            @foreach($goal->objectives as $objective)
                                                <li>
                                                    <h5> {{ $objective->name }} </h5>
                                                     <h6>{{__('Functions')}}:</h6>
                                                    <ul>
                                                        @foreach($objective->functs as $function)
                                                            <li>{{ $function->name }}</li>
                                                        @endforeach
                                                        @if(Auth::user()->hasRole('quality-engineer','admin'))      
                                                        <li>
                                                            <a href="{{route('funs.create')}}?goal_id={{$goal->id}}&objective_id={{$objective->id}}" class = "btn btn-primary">
                                                                {{__('Add Function')}}
                                                            </a>
                                                        </li>
                                                        @endif
                                                    </ul>
                                                </li>
                                            @endforeach
                                            @if(Auth::user()->hasRole('quality-engineer','admin'))
                                            <li>
                                                <a href="{{route('objectives.create')}}?goal_id={{$goal->id}}" class = "btn btn-success w-100">
                                                    {{__('Add Objective')}}
                                                </a>
                                            </li>
                                            @endif
                                        </ul>
                                    </td>
                                <td>
                                    <a href="{{ route('goals.show', $goal->id) }}" class="btn btn-default">{{__('View')}}</a>
                                    @if(Auth::user()->hasRole('quality-engineer','admin'))
                                    <a href="{{ route('goals.edit', $goal->id) }}" class="btn btn-warning">{{__('Edit')}}</a>
                                    <form style="display:inline-block" method="POST" action="{{ route('goals.destroy', $goal->id) }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="form-control btn btn-danger">{{__('Delete')}}</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection