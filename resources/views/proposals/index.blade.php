@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Proposals')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('proposals.create') }}">{{__('Create')}}</a>
            </div>
        </div> 
        <div class="row mt-4">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Title')}}</th>
                            <th>{{__('Description')}}</th>
                            <th>{{__('Status')}}</th>
                            <th>{{__('Decision')}}</th>
                            <th>{{__('Actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($proposals as $proposal)
                        <tr>
                            <td>{{ $proposal->id }}</td>
                            <td>{{ $proposal->title }}</td>
                            <td>{{ $proposal->description }}</td>
                            <td>{{ $proposal->status }}</td>
                            <td>{{ $proposal->decision }}</td>
                            <td>
                                <a class="btn btn-info" href="{{ route('proposals.show', $proposal->id) }}">{{__('View')}}</a>
                                <a class="btn btn-primary" href="{{ route('proposals.edit', $proposal->id) }}">{{__('Edit')}}</a>
                                <form action="{{ route('proposals.destroy', $proposal->id) }}" method="POST" style="display:inline;">
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
