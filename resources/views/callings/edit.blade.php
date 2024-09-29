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
                <h1>{{ __('Edit Form Calling') }}</h1>
                
                <form method="POST" action="{{ route('callings.update', $calling->id) }}">
                    @csrf
                    @method('PUT') <!-- Обязательно указать метод PUT для обновления -->
                    
                    <!-- Vyklyk-na-robotu  radio-->
                    <div class="form-group">
                        @foreach($Vyklyk_na_robotu_ids as $Vyklyk_na_robotu_id)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="vyklyk_na_robotu" name="vyklyk_na_robotu" value="{{ $Vyklyk_na_robotu_id->id }}"                         >
                            <label class="form-check-label" for="vyklyk_na_robotu">
                                {{ __($Vyklyk_na_robotu_id->name) }}
                            </label>
                        </div>                           
                        @endforeach
                    </div>

                    <!-- Description textarea -->
                    <div class="form-group">
                        <label for="description">{{ __('Work description') }}:</label>
                        <textarea id="description" rows="7" class="form-control" name="description">{{ old('description', $calling->description) }}</textarea>
                    </div>
                    
                    <!-- Arrival time (date-time input) -->
                    <div class="form-group">
                        <label for="arrival_time">{{ __('Arrival Time') }}</label>
                        <input type="datetime-local" id="arrival_time" class="form-control" name="arrival_time" value="{{ old('arrival_time', $calling->arrival_time ? \Carbon\Carbon::parse($calling->arrival_time)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    

                    <!-- Personal arrival checkbox -->
                    <div class="form-group" style="display: none" >
                        <label for="personal_arrival_id">{{ __('Personal Arrival') }}</label>
                        <input type="checkbox" id="personal_arrival_id" name="personal_arrival_id" value="1" {{ old('personal_arrival_id', $calling->personal_arrival_id) ? 'checked' : '' }}>
                    </div>
                    
                    <!-- Start time (date-time input) -->
                    <div class="form-group">
                        <label for="start_time">{{ __('Start Time') }}</label>
                        <input type="datetime-local" id="start_time" class="form-control" name="start_time" value="{{ old('start_time', $calling->start_time ?  \Carbon\Carbon::parse($calling->start_time)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <!-- Personal start checkbox -->
                    <div class="form-group" style="display: none">
                        <label for="personal_start_id">{{ __('Personal Start') }}</label>
                        <input type="checkbox" id="personal_start_id" name="personal_start_id" value="1" {{ old('personal_start_id', $calling->personal_start_id) ? 'checked' : '' }}>
                    </div>            

                    <!-- Work time (date-time input) -->
                    <div class="form-group">
                        <label for="work_time">{{ __('Work Time') }}</label>
                        <input type="datetime-local" id="work_time" class="form-control" name="work_time" value="{{ old('work_time', $calling->work_time ? \Carbon\Carbon::parse($calling->work_time)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <!-- Personal work checkbox -->
                    <div class="form-group" style="display: none">
                        <label for="personal_work_id">{{ __('Personal Work') }}</label>
                        <input type="checkbox" id="personal_work_id" name="personal_work_id" value="1" {{ old('personal_work_id', $calling->personal_work_id) ? 'checked' : '' }}>
                    </div>

                    <!-- End time (date-time input) -->
                    <div class="form-group">
                        <label for="end_time">{{ __('End Time') }}</label>
                        <input type="datetime-local" id="end_time" class="form-control" name="end_time" value="{{ old('end_time', $calling->end_time ? \Carbon\Carbon::parse($calling->end_time)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <!-- Personal end checkbox -->
                    <div class="form-group" style="display: none">
                        <label for="personal_end_id">{{ __('Personal End') }}</label>
                        <input type="checkbox" id="personal_end_id" name="personal_end_id" value="1" {{ old('personal_end_id', $calling->personal_end_id) ? 'checked' : '' }}>
                    </div>

                    @php 
                    $workers = \App\Models\Personal:: all();
                    @endphp
                    
                    <!-- Workers (multi-select) -->
                    <div class="form-group">
                        <label for="workers">{{ __('Workers') }}</label>
                        <select id="workers" class="form-control" name="workers[]" multiple>
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}" {{ in_array($worker->id, old('workers', $calling->workers->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $worker->fio }}
                                </option>
                            @endforeach
                        </select>
                    </div>                    
                    
                    <!-- Chief (dropdown select from selected workers) -->
                    <div class="form-group">
                        <label for="chief">{{ __('Chief') }} {{__('and')}}  {{__('Payment')}} </label>
                        <div class="container" id="show_workers" ></div>
                    </div>
                    
                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary w-100">{{ __('Update') }}</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        const workers = @json($workers);
        const types_payment = @json($Oplata_pratsi_ids);
        const selectedWorkers = @json($calling->workers->pluck('id'));
        const selectedChief = @json($calling->chief_id);

        document.getElementById('workers').addEventListener('change', function() {
            const workersSelect = document.getElementById('workers');
            const showWorkers = document.getElementById('show_workers');
            
            // Clear existing rows
            showWorkers.innerHTML = '';

            // Loop through selected workers
            Array.from(workersSelect.selectedOptions).forEach(option => {
                let workerId = option.value;
                let workerName = option.text;

                // Create a new row for each selected worker
                let row = `
                    <div class="row mb-3">
                        <div class="col-md-6">
                            ${workerName}
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="payments[${workerId}]">
                                ${types_payment.map(type => `<option value="${type.id}" ${type.id == 1 ? 'selected' : ''}>${type.name}</option>`).join('')}
                            </select>
                        </div>
                        <div class="col-md-3">
                            {{__('Chief')}}
                            <input type="radio" name="chief" value="${workerId}" ${selectedChief == workerId ? 'checked' : ''}>
                        </div>
                    </div>
                `;
                showWorkers.innerHTML += row;
            });
        });
        // Trigger the change event initially to populate selected workers
        document.getElementById('workers').dispatchEvent(new Event('change'));
        
        // Other JavaScript code for handling time inputs and color-coding...
    </script>    
@endsection
