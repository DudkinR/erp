@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Facts')}}  </h1>
                <a class="text-right" href="{{ route('facts.create') }}">{{__('Create fact')}}</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th> {{__('Date')}} </th>
                            <th> {{__('Name')}} </th>
                            <th> {{__('Description')}} </th>
                            <th>{{__('Image')}}</th>
                       </tr>
                    </thead>
                    <tbody>
                        @foreach($facts as $fact)
                            <tr>
                                <td>{{ $fact->date }}</td>
                                <td>{{ $fact->name }}</td>
                                <td>{!! $fact->description !!}
                                    <a href="{{ route('facts.show', $fact) }}">{{__('View')}}</a>
                                    <a href="{{ route('facts.edit', $fact) }}">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('facts.destroy', $fact) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit">{{__('Delete')}}</button>
                                    </form>

                                </td>
                                <td><img src="{{ asset('storage/'.$fact->image) }}" alt="{{ $fact->name }}" style="max-width: 100px;"></td>
                               </tr>
                           
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection