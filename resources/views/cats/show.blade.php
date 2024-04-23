@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{__('category')}}
                </h1>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h1> {{$category->name}} </h1>
                                <a href="{{ route('cats.edit',  $category) }}" class="btn btn-warning">{{__('Edit')}}</a>
                                <form method="POST" action="{{ route('cats.destroy', $category) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <img src="{{ asset('images/'.$category->image) }}" alt="{{ $category->name }}" 
                                class="img-fluid float-right w-100" 
                                >
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{__('Created on')}}: {{ date('Y-m-d', strtotime($category->created_at)) }}</p>
                        <p>{{ $category->description }}</p>

                    </div>
                    <div class="card-footer">
                        <?php $cats = \App\Models\Category::all(); ?>
                        @foreach ($cats as $cat)
                            @if ($cat->id == $category->parent_id)
                                <p>{{__('Parent')}}: {{ $cat->name }}</p>
                            @endif
                        @endforeach
                        
                </div>
            </div>
        </div>
    </div>
@endsection
