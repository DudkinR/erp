@extends('layouts.app')
@section('content')
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Divisions')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin'))
                <a class="text-right" href="{{ route('divisions.create') }}">{{__('Create')}}</a>
                @endif
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
                        @foreach($divisions as $division)
                            <tr>
                                <td>{{ $division->id }}</td>
                                <td>
                                    @if($division->parent)
                                        {{ $division->parent->name }}:
                                    @endif
                                    {{ $division->name }}
                                </td>
                                <td>{{ $division->description }}</td>
                                <td><a href="{{ route('divisions.show', $division->id) }}">{{__('Show')}}</a>
                                    @if(Auth::user()->hasRole('quality-engineer','admin'))
                                    <a href="{{ route('divisions.edit', $division->id) }}">{{__('Edit')}}</a>
                                    
                                    <form action="{{ route('divisions.destroy', $division->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">{{__('Delete')}}</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
              
            </div> 
    </div>
@endsection