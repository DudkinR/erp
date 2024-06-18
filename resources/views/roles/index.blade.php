@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Roles')}}</h1>
                <a class="text-right" href="{{ route('roles.create') }}">{{__('Create')}}</a>
            </div>
        </div>  
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>
                                    <a href="{{ route('roles.show', $role->id) }}">
                                    {{ $role->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('roles.edit', $role->id) }}">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
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

    </div>
@endsection