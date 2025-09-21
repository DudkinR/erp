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
            <h1>{{__('Teams')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('teams.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        <div class="row">
        <div class="col-md-12 mt-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">{{__('ID')}}</th>
                        <th width="30%">{{__('Name')}}</th>
                        <th width="50%">{{__('Description')}}</th>
                        <th width="15%">{{__('Actions')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                    <tr>
                        <td>{{ $team->id }}</td>
                        <td>{{ $team->name }}</td>
                        <td>{{ $team->description }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('teams.show', $team->id) }}">{{__('View')}}</a>
                            <a class="btn btn-primary" href="{{ route('teams.edit', $team->id) }}">{{__('Edit')}}</a>
                            <form action="{{ route('teams.destroy', $team->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('{{__('Are you sure?')}}')">{{__('Delete')}}</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>   
    </div>
@endsection