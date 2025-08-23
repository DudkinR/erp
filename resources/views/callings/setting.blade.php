@extends('layouts.app')
@section('content')

    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success">{{ __(session('success')) }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ __(session('error')) }}</div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('Setting') }}</h1>
                <form method="POST" action="{{ route('callings.storesetting') }}">
                    @csrf
                  <!-- Arrival time (date-time input) -->
                    <div class="form-group">
                        <label for="Oplata_pratsi">{{ __('Payment of work') }}</label>
                        <input type="hidden" id="Oplata_pratsi" class="form-control" name="Oplata_pratsi" value="Oplata-pratsi">
                    </div>

                    @php 
                    // where pivot division == division_id of authorized user
                    $types = \App\Models\Type:: all()->keyBy('id')->values();
                    $Oplata_pratsi = \App\Models\Type::where('slug', 'Oplata-pratsi')->first();
                    @endphp
                    <!-- Workers (multi-select) -->
                    <div class="form-group">
                        <label for="types">{{ __('Type') }}</label>
                        <select id="type" class="form-control" name="type">
                            @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>                                
                            @endforeach
                        </select>
                    </div>                    
                    <!-- Chief (dropdown select from selected workers) -->
                    <div class="form-group">
                        <label for="chief">{{ __('Chief') }}</label>
                        <select id="chief" class="form-control" name="chief">
                            <option value="">{{ __('Select Chief') }}</option>
                        </select>
                    </div>
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary w-100">{{ __('Create') }}</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        const workers =@json($workers);
        console.log(workers);
        document.getElementById('workers').addEventListener('change', function() {
        const workersSelect = document.getElementById('workers');
        const chiefSelect = document.getElementById('chief');

        // Clear chief options
        chiefSelect.innerHTML = '<option value="">{{ __('Select Chief') }}</option>';

        // Populate chief dropdown with selected workers
        Array.from(workersSelect.selectedOptions).forEach(option => {
            const newOption = document.createElement('option');
            newOption.value = option.value;
            newOption.text = option.text;
            chiefSelect.add(newOption);
        });
    });
        // Если установлено время начала, отметить чекбокс персонального начала
        document.getElementById('start_time').addEventListener('change', (e) => {
            if (e.target.value) {
                document.getElementById('personal_start_id').checked = true;
            }
        });
    
        // Если установлено время прибытия, отметить чекбокс персонального прибытия
        document.getElementById('arrival_time').addEventListener('change', (e) => {
            if (e.target.value) {
                document.getElementById('personal_arrival_id').checked = true;
            }
        });
    
        // Если установлено время начала работы, отметить чекбокс персональной работы
        document.getElementById('work_time').addEventListener('change', (e) => {
            if (e.target.value) {
                document.getElementById('personal_work_id').checked = true;
            }
        });
    
        // Преобразование строки времени в объект Date
        function parseDateTime(input) {
            return input ? new Date(input) : null;
        }
    
        // Если время прибытия > времени начала, показывать ошибку цветом
        document.getElementById('start_time').addEventListener('blur', (e) => {
            const arrivalTime = parseDateTime(e.target.value);
            const startTime = parseDateTime(document.getElementById('start_time').value);
            
            if (arrivalTime && startTime && arrivalTime > startTime) {
                document.getElementById('arrival_time').style.backgroundColor = 'red';
                document.getElementById('start_time').style.backgroundColor = 'red';
            } else {
                document.getElementById('arrival_time').style.backgroundColor = '#FFF666';
                document.getElementById('start_time').style.backgroundColor = '#FFF545';
            }
        });
    
        // Если время начала > времени начала работы, показывать ошибку цветом
        document.getElementById('work_time').addEventListener('blur', (e) => {
            const startTime = parseDateTime(e.target.value);
            const workTime = parseDateTime(document.getElementById('work_time').value);
            
            if (startTime && workTime && startTime > workTime) {
                document.getElementById('start_time').style.backgroundColor = 'red';
                document.getElementById('work_time').style.backgroundColor = 'red';
            } else {
                document.getElementById('start_time').style.backgroundColor = '#FFF';
                document.getElementById('work_time').style.backgroundColor = '#FFF';
            }
        });
    
    </script>
    
@endsection
