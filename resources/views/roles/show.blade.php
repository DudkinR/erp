@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Role')}}</h1>
                <a class="text-right
                " href="{{ route('roles.index') }}">{{__('Back')}}</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ $role->name }}</div>
                    <div class="card-body">
                        <p>{{ $role->description }}</p>
                        <p>{{ $role->slug }}</p>
                        <a href="{{ route('roles.edit', $role->id) }}">Edit</a>
                        <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit">Delete</button>
                        </form>
                    </div>
               </div>
            </div>
        </div>
   </div>
@endsection