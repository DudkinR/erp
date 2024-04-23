@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('cats')}}  </h1>
                <a class="text-right" href="{{ route('cats.create') }}">{{__('Create category')}}</a>
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
                                    <a href="{{ route('cats.show', $category) }}">{{__('View')}}</a>
                                    <a href="{{ route('cats.edit', $category) }}">{{__('Edit')}}</a>
                                    <form method="POST" action="{{ route('cats.destroy', $category) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit">{{__('Delete')}}</button>
                                    </form>

                                </td>
                                <td><img src="{{ asset('imagesCat/'.$category->image) }}" alt="{{ $category->name }}" style="max-width: 100px;"></td>
                               </tr>
                           
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection