@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row mb-3">
        <div class="col-md-12">
            <h1>{{ __('My Words') }}</h1>
            <a class="btn btn-primary w-100" href="{{ route('words.create') }}">
                {{ __('Create') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if($words->isEmpty())
                <div class="alert alert-info">
                    {{ __('You have no words yet.') }}
                </div>
            @else
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('Bedword') }}</th>
                            <th>{{ __('Comment') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($words as $word)
                            <tr>
                                <td>{{ $word->id }}</td>
                                <td>{{ $word->bedword }}</td>
                                <td>{{ $word->comment }}</td>
                                <td 
                                    @class([
                                        'bg-warning text-dark' => $word->type == 1, // Yellow
                                        'bg-danger text-white' => $word->type == 2, // Red
                                        'bg-primary text-white' => $word->type == 3, // Blue
                                    ])
                                >
                                    {{ $word->type }}
                                </td>
                                <td>
                                    <a href="{{ route('words.edit', $word) }}" class="btn btn-sm btn-warning">
                                        {{ __('Edit') }}
                                    </a>
                                    <form action="{{ route('words.destroy', $word) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</div>
@endsection