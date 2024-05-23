@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('tasks')}}</h1>
                <a class="text-right" href="{{ route('tasks.index') }}">{{__('Back to tasks')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center @if($task->status == 'new') bg-warning @elseif($task->status == 'completed') bg-success @endif">
                    {{ $task->step->name }} ({{ $task->stage->name }})
                </h2>
                <h3>{{ $task->step->description }}</h3>
            </div>
        </div>
        <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @if($task->type == 'photo')
                <div id="image_preview" class="form-group"></div>
                <div class="form-group">
                    <label for="image">{{__('Image')}}</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" capture="environment">
                </div>
            @elseif($task->type == 'doc')
                @include('components.input_file_doc')
            @endif

            @if($task->count > 0)
                <div class="row">
                    <div class="col-md-12">
                        <h2>{{__('Count')}}</h2>
                        <input type="number" name="count" class="form-control" value="{{$task->count}}">
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <select name="status" class="form-control">
                        <option value="completed" @if($task->status == 'completed') selected @endif>{{__('Completed')}}</option>
                        <option value="new" @if($task->status == 'new') selected @endif>{{__('New')}}</option>
                        <option value="problem" @if($task->status == 'problem') selected @endif>{{__('Problem')}}</option>
                    </select>
                    <button  class="btn btn-warning mt-2" >{{__('Save')}}</button>
                </div>
                <div class="col-md-4" id="new_problem" style="display: none;">
                    <a href="{{ route('problems.create', $task->id) }}" class="btn btn-danger mt-2">{{__('Add problem')}}</a>
                </div>
            </div>
        </form>
    </div>

    <script>
            const newProblem = document.getElementById('new_problem');
            const status = document.querySelector('select[name="status"]');
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('image_preview');

            status.addEventListener('change', function() {
                if (status.value === 'problem') {
                    newProblem.style.display = 'block';
                } else {
                    newProblem.style.display = 'none';
                }
            });

            imageInput.addEventListener('change', function() {
                imagePreview.innerHTML = ''; // Clear previous image
                const file = imageInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.classList.add('img-fluid', 'mt-2');
                        imagePreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                }
            });

            document.querySelector('form').addEventListener('submit', function(event) {
                if (status.value === 'completed' && !imageInput.value) {
                    event.preventDefault();
                    alert('Please take a picture');
                }
            });
    </script>
@endsection
