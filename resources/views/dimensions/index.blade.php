@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Dimension')}}</h1>
                <a class="text-right" href="{{ route('dimensions.create') }}">{{__('Create new')}}</a>
            </div>
        </div>
            @foreach($dimensions as $dimension)
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                {{ $dimension->name }}
                            </div>
                            <div class="card-body">
                                <p>{{ $dimension->description }}</p>
                                <a href="{{ route('dimensions.edit',$dimension) }}">{{__('Edit')}}</a>
                                <hr>
                                <form method="POST" action="{{ route('dimensions.destroy',$dimension) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit">{{__('Delete')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>    
    </div>
@endsection