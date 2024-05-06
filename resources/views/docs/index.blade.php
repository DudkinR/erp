@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Docs')}}  </h1>
                <a class="text-right" href="{{ route('docs.create') }}">{{__('Create Doc')}}</a>
                 <table class="table">
                    <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Path')}}</th>
                            <th>{{__('Slug')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($docs as $doc)
                            <tr>
                                <td>{{ $doc->name }}</td>
                                <td>{!! $doc->path !!}</td>
                                <td>{{ $doc->slug }}</td>
                                <td>{{ $doc->description }}</td>                              
                                <td>{{ $doc->category_id }}</td>
                                <td>
                                    <a href="{{ route('docs.edit', $doc->id) }}" class="btn btn-warning">{{__('Edit')}}</a>
                                    <a href="{{ route('docs.show', $doc->id) }}" class="btn btn-primary">{{__('View')}}</a>
                                    <form method="POST" action="{{ route('docs.destroy', $doc->id) }}" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
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