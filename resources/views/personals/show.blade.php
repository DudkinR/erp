@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Personal show</h1>
                <a class="text-right
                " href="{{ route('personal.index') }}">Back</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        {{ $personal->name }}
                    </div>
                    <div class="card-body">
                        <p>{{ $personal->description }}</p>
                        <p><strong>Due Date:</strong> {{ $personal->due_date }}</p>
                        <h2>Functions</h2>
                        <ul>
                            @foreach($personal->funs as $fun)
                                <li>{{ $fun->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection