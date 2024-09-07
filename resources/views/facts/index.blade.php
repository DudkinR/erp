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
                <h1>{{__('Facts')}}  </h1>
                <a class="btn btn-info w-100" href="{{ route('facts.create') }}">{{__('Create fact')}}</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th> {{__('Date create')}} </th>
                            <th> {{__('Name')}} </th>
                            <th> {{__('Description')}} </th>
                            <th>{{__('Image')}}</th>
                       </tr>
                    </thead>
                    <tbody>
                        @foreach($facts as $fact)
                            <tr>
                                <td> {{ date('Y-m-d', strtotime($fact->created_at)) }} </td>
                                <td>{{ $fact->name }}</td>
                                <td>{!! $fact->description !!}
                                    <a href="{{ route('facts.show', $fact) }}">{{__('View')}}</a>
                                    @if(Auth::user()->hasRole('quality-engineer','admin'))
                                    <a href="{{ route('facts.edit', $fact) }}">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('facts.destroy', $fact) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit">{{__('Delete')}}</button>
                                    </form>
                                    @endif

                                </td>
                                <td>
                                    @foreach($fact->images as $image)
                                    <img src="{{$image->path }}" alt="{{ $fact->name }}" style="max-width: 100px;">
                                    @endforeach
                                </td>
                               </tr>
                           
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection