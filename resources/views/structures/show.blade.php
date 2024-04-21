@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Structure</h1>
                <a class="text-right
                " href="{{ route('structures.index') }}">Back</a>
            </div>
        </div>
        <div class="row">
                <div class="card">
                    <div class="card-header">
                        {{ $structure->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $structure->description }}</p>
                        <p><strong>Due Date:</strong> {{ $structure->due_date }}</p>
                        <h2>Functions</h2>
                        <ul>
                            @foreach($structure->funs as $fun)
                                <li>{{ $fun->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection