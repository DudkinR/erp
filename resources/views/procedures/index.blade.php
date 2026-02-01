@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Procedures')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('procedures.create') }}">{{__('Create')}}</a>
            </div>
        </div>    
        {{-- Таблиця процедур --}}
    <div class="row">
        <div class="col-md-12">
            @if($procedures->count())
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ __('Name') }}</th>
                            <th scope="col">{{ __('Description') }}</th>
                            <th scope="col">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($procedures as $procedure)
                            <tr>
                                <td>{{ $procedure->id }}</td>
                                <td>{{ $procedure->name }}</td>
                                <td>{{ Str::limit($procedure->description, 50) }}</td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('procedures.show', $procedure->id) }}" 
                                       class="btn btn-info btn-sm">
                                        {{ __('Show') }}
                                    </a>
                                    <a href="{{ route('procedures.edit', $procedure->id) }}" 
                                       class="btn btn-warning btn-sm">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('procedures.destroy', $procedure->id) }}" 
                                          method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    {{ __('No procedures found.') }}
                </div>
            @endif
        </div>
    </div>

    </div>
@endsection