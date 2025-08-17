@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Steps')}}</h1>
            @if(Auth::user()->hasRole('admin'))
                <a class="text-right" href="{{ route('steps.create') }}">{{__('New')}}</a>
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
                        @foreach($steps as $step)
                        <tr>
                            <td>{{ $step->id }}</td>
                            <td>{{ $step->name }}</td>
                            <td>{!! nl2br(e($step->description)) !!}</td>
                            <td>
                                  <a href="{{ route('steps.show',$step) }}" class="btn btn-success w-100">{{__('Show')}}</a>
                                  @if(Auth::user()->hasRole('admin'))
                                <a href="{{ route('steps.edit',$step) }}" class="btn btn-primary w-100">{{__('Edit')}}</a>
                               <a href="{{ route('steps.copy_step',$step) }}" class="btn btn-warning w-100">{{__('Copy')}}</a>
                              
                                <form method="POST" action="{{ route('steps.destroy',$step) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger w-100">{{__('Delete')}}</button>
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