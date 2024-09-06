@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('controls')}}</h1>
            @if(Auth::user()->hasRole('quality-engineer','admin'))
                <a class="text-right" href="{{ route('controls.create') }}">{{__('control add')}}</a>
                @endif
            </div>
        </div>    
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('Control')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Steps')}}</th>
                            <th>{{__('Dimensions')}}</th>
                            @if(Auth::user()->hasRole('quality-engineer','admin'))
                            <th>{{__('Actions')}}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($controls as $control)
                            <tr>
                                <td>
                                    <a href="{{ route('controls.show',$control) }}">{{ $control->name }}</a>
                                </td>
                                <td>{{ $control->description }}</td>
                                <td>
                                    @foreach($control->steps as $step)
                                        <a href="{{route("steps.show",$step->id)}}">{{ $step->name }}</a>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($control->dimensions as $dimension)
                                        <a href="{{route("dimensions.show",$dimension->id)}}">{{ $dimension->name }}</a>
                                    @endforeach
                                </td>
                                @if(Auth::user()->hasRole('quality-engineer','admin'))
                                <td>
                                    
                                    <a href="{{ route('controls.edit',$control) }}" class="btn btn-primary">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('controls.destroy',$control) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                                    </form>

                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
    </div>
@endsection