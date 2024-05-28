@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('tasks') }}</h1>
                <a class="text-right" href="{{ route('tasks.index') }}">{{ __('Back to tasks') }}</a>
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
                    <label for="image">{{ __('Image') }}</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" capture="environment">
                </div>
            @elseif($task->type == 'doc')
                @include('components.input_file_doc')
            @endif

            @if($task->count > 0)
                <div class="row">
                    <div class="col-md-12">
                        <h2>{{ __('Count') }}</h2>
                        <input type="number" name="count" id="count_id" class="form-control" value="{{ $task->count }}">
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-md-4">
                    <select name="status" id="status_task" class="form-control">
                        <option value="completed" @if($task->status == 'completed') selected @endif>{{ __('Completed') }}</option>
                        <option value="new" @if($task->status == 'new') selected @endif>{{ __('New') }}</option>
                        <option value="problem" @if($task->status == 'problem') selected @endif>{{ __('Problem') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-warning mt-2" id="save_button"
                    @if($task->status != 'completed') style="display: none;" @endif
                     >{{ __('Save') }}</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="problemModal" tabindex="-1" role="dialog" aria-labelledby="problemModalLabel" aria-hidden="true">
       
    <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="problemModalLabel">{{ __('Problem') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea name="problem" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @php $positions = \App\Models\Position::all(); @endphp
                        <select name="position" class="form-control">
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}">{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const need_count = {{ $task->count }};
        const count_id = document.getElementById('count_id');
        const saveButton = document.getElementById('save_button');
        const status_task = document.getElementById('status_task');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image_preview');
        const url_images = {
            @foreach($task->images as $image)
                '{{ $image->id }}': '{{ $image->path }}',
            @endforeach
        }
        function showImagePreview() {
         // div id = image_preview

            imagePreview.innerHTML = '';
            for (const id in url_images) {
                const img = document.createElement('img');
                img.src = url_images[id];
                // style="max-width: 300px; max-height: 300px;
                img.style = 'max-width: 300px; max-height: 300px;';
                                img.className = 'img-fluid';

                img.alt = id;
                imagePreview.appendChild(img);
            }
        }
        showImagePreview();

        status_task.addEventListener('change', function() {
            if (status_task.value === 'problem') {
                $('#problemModal').modal('show');
                saveButton.style.display = 'none';
            } 
            else if (status_task.value === 'completed' && imagePreview.innerHTML === '') {
                alert('{{ __('Please take a picture') }}');
                status_task.value = 'new';
                saveButton.style.display = 'none';
            }
            else if (status_task.value === 'completed' && count_id.value != need_count) {
                alert('{{ __('Please enter the correct count') }}');
                status_task.value = 'new';
                saveButton.style.display = 'none';
            }
            else if (status_task.value === 'completed') {
                saveButton.style.display = 'block';
            }
            else {
                saveButton.style.display = 'none';
            }
        });



        document.querySelector('form').addEventListener('submit', function(event) {
            if (status_task.value === 'completed' && !imageInput.value) {
                event.preventDefault();
                alert('Please take a picture');
            }
        });
        // send form with ajax modal window
        $('#problemModal button.btn-primary').click(function() {
    var problemText = $('#problemModal textarea').val();
    var position = $('#problemModal select').val();
    if (problemText.trim() === '') {
        alert('Please enter a problem description');
        return;
    }

    var dt = {
        '_token': '{{ csrf_token() }}',
        'project_id': '{{ $task->project_id }}',
        'task_id': '{{ $task->id }}',
        'stage_id': '{{ $task->stage_id }}',
        'step_id': '{{ $task->step_id }}',
        'user_id': '{{ Auth::id() }}',
        'position': position,
        'status': 'problem',
        'problem': problemText
    };

    const url_back = '{{ route('tasks.index') }}';
    const url = '{{ route('tasks.problem') }}';

    console.log('Sending data:', dt); // Используйте 'dt' вместо 'data'

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': dt._token // Добавлено для обеспечения CSRF защиты
        },
        body: JSON.stringify(dt)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Success:', data);
        // Переход на страницу tasks
        window.location.href = url_back;
    })
    .catch((error) => {
        console.error('Error:', error);
    });
});

    </script>
@endsection
