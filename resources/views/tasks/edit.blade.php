@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('tasks')}}</h1>
                <form method="POST" action="{{ route('tasks.update',$goal) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                   
                    <button type="submit" class="btn btn-primary">{{__('Edit')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection