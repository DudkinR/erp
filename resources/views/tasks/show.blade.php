@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('tasks')}}</h1>
                <a class="text-right
                " href="{{ route('tasks.index') }}"> {{__('Back to tasks')}}</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2>{{ $task->name }}</h2>
                <p>{{ $task->description }}</p>
                <p>{{ $task->status }}</p>
                <p>{{ $task->project->name }}</p>
                <p>{{ $task->stage->name }}</p>
                <p>{{ $task->step->name }}</p>

            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <form action="{{ route('tasks.img.create', $task->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" name="image" id="image" class="form-control" required>
                        <button type="submit" class="btn btn-primary">
                            {{__('Add image')}}
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn btn-warning">
                       {{__('Completed') }}
                    </button>
                </form>
            </div>
            <div class="col-md-4">
                 <a href="{{ route('problems.create', $task->id) }}" class="btn btn-danger">
                    {{__('Add problem')}}
                </a>
            </div>
        </div>
   </div>
@endsection