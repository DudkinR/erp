@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('cats')}}  </h1>
                @if(Auth::user()->hasRole('quality-engineer','admin'))
                <a class="text-right" href="{{ route('cats.create') }}">{{__('Create Category')}}</a>
                @endif
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
                                    @if(Auth::user()->hasRole('quality-engineer','admin'))
                                    <a href="{{ route('cats.edit', $category) }}" class = "btn btn-warning">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('cats.destroy', $category) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class = "btn btn-danger">{{__('Delete')}}</button>
                                    </form>

                                    <hr>
                                    {{__('New Under Category')}}
                                    <a href="{{ route('cats.create', ['parent_id' => $category->id]) }}" class = "btn btn-success ">{{__('Create')}}</a>
                                    @endif
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