@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Goal</h1>
                <div class="card">
                    <div class="card-header">
                        {{ $goal->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $goal->description }}</p>
                        <p><strong>Due Date:</strong> {{ $goal->due_date }}</p>
                        <h2>Functions</h2>
                        <ul>
                            @foreach($goal->funs as $fun)
                                <li>{{ $fun->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
