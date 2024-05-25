@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('cats')}}  </h1>
                <a class="text-right" href="{{ route('cats.create') }}">{{__('Create Category')}}</a>
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
                        @foreach($categories as $category)
                            <tr>
                                <td> {{ date('Y-m-d', strtotime($category->created_at)) }} </td>
                                <td>{{ $category->name }}</td>
                                <td>{!! $category->description !!}
                                    <hr>
                                    <a href="{{ route('cats.show', $category) }}" class = "btn btn-info" >{{__('View')}}</a>
                                    <a href="{{ route('cats.edit', $category) }}" class = "btn btn-warning">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('cats.destroy', $category) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class = "btn btn-danger">{{__('Delete')}}</button>
                                    </form>
                                    <hr>
                                    {{__('New Under Category')}}
                                    <a href="{{ route('cats.create', ['parent_id' => $category->id]) }}" class = "btn btn-success ">{{__('Create')}}</a>

                                </td>
                                <td>
                                    @foreach($category->images as $image)
                                    
                                        <img src="{{ $image->path }}" alt="{{ $image->name }}" style="max-width: 100px;">
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