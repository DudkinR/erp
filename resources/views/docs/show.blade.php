@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{__('doc')}}
                </h1>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h1> {{$doc->name}} </h1>
                                <a href="{{ route('docs.edit',  $doc) }}" class="btn btn-warning">{{__('Edit')}}</a>
                                <form method="POST" action="{{ route('docs.destroy', $doc) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                               path : {{$doc->path}}

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{__('Created on')}}: {{ date('Y-m-d', strtotime($doc->created_at)) }}</p>
                        <p>{{ $doc->description }}</p>

                    </div>

            </div>
        </div>
    </div>
@endsection
