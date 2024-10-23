@extends('layouts.app')
@section('content')
@php                     
$workers = $personnelInSameDivisions ? $personnelInSameDivisions : [];
@endphp
<style>
    /* Custom styles */
    .container {
        margin-top: 40px;
    }
    
    .form-group label {
        font-weight: bold;
        color: #4c4c4c;
    }

    /* Style for the radio buttons group */
    .form-check-input {
        margin-right: 10px;
    }

    .form-check-label {
        font-size: 16px;
    }

    /* Adding padding and shadow to the form sections */
    .form-section {
        background-color: #f9f9f9;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    /* Style for the buttons */
    .btn {
        margin-top: 15px;
    }

    /* Style for alerts */
    .alert {
        font-size: 14px;
    }
</style>

<div class="container" style="margin-top: 40px; margin-bottom: 40px; padding: 20px; background-color: #b8b1b1; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    <!-- Error and success messages -->
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
            <h1 class="text-center">{{ __('New Form Calling') }}</h1>
            <form method="POST" action="{{ route('callings.store') }}" >
                @csrf
                
                <!-- Form section -->
                <div class="container">
                    <div class="row">
                        <!-- Первый столбец -->
                        <div class="col-md-6">
                            <div class="form-section">
                               <!-- Поле выбора вызова на работу -->
                                <div class="form-group mb-3">
                                    <h2>{{ __('Call to Work Type') }}</h2>
                                    @php $Vyklyk = 0; @endphp
                                    @foreach($DI['Vyklyk_na_robotu_ids'] as $Vyklyk_na_robotu_id)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="vyklyk_na_robotu_{{ $Vyklyk_na_robotu_id->id }}" name="vyklyk_na_robotu" 
                                            value="{{ $Vyklyk_na_robotu_id->id }}" 
                                            onclick="Select_type_of_work({{ $Vyklyk_na_robotu_id->id }})"
                                            {{ old('vyklyk_na_robotu') == $Vyklyk_na_robotu_id->id ? 'checked' : '' }} 
                                            @if($Vyklyk == 0) checked @endif>
                                        <label class="form-check-label" for="vyklyk_na_robotu_{{ $Vyklyk_na_robotu_id->id }}">
                                            {{ __($Vyklyk_na_robotu_id->name) }}
                                        </label>
                                    </div>
                                    @php $Vyklyk = 1; @endphp
                                    @endforeach
                                </div>
                
                               
                            </div>
                        </div>
                
                        <!-- Второй столбец -->
                        <div class="col-md-6">
                            <div class="form-section"> <!-- Arrival time (date-time input) -->
                                <div class="form-group mb-3">
                                    <h2 for="arrival_time">{{ __('Arrival Time') }}</h2>
                                    <input type="datetime-local" id="arrival_time" class="form-control" name="arrival_time" value="{{ old('arrival_time') }}">
                                </div>
                                <!-- Start time (date-time input) -->
                                <div class="form-group mb-3">
                                    <h2 for="start_time">{{ __('Start Time') }}</h2>
                                    <input type="datetime-local" id="start_time" class="form-control" name="start_time" value="{{ old('start_time') }}">
                                </div>
                
                                <!-- End time (date-time input) -->
                                <div class="form-group mb-3">
                                    <h2 for="end_time">{{ __('End Time') }}</h2>
                                    <input type="datetime-local" id="end_time" class="form-control" name="end_time" value="{{ old('end_time') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                

                <!-- Description textarea -->
                <div class="form-section">
                    <div class="form-group">
                        <h2 for="description">{{ __('Work description') }}:</h2>
                        <textarea id="description" rows="7" class="form-control" name="description" required
                        >{{ old('description') }}</textarea>
                    </div>
                </div>
               <!-- Поле выбора типа работы -->
                <div class="form-section">
                    <div class="form-group">
                        <h2 for="Type_of_work">{{ __('Type of work') }}:</h2>
                        <select id="Type_of_work" class="form-control" name="Type_of_work" onchange="DisplayWorkInfo(this.value)" required>
                            <option value="">{{ __('Select work type') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Поле для отображения описания -->
                <div class="form-section">
                    <div class="container" id="work_info" >
                    </div>
                </div>
                

                <!-- Workers multi-select -->
                <div class="form-section">
                    <div class="form-group">
                        <h2 for="workers">{{ __('Workers') }}</h2>
                        <select id="workers" class="form-control" name="workers[]" multiple>
                            @foreach($workers as $worker)
                                <option value="{{ $worker->id }}" {{ in_array($worker->id, old('workers', [])) ? 'selected' : '' }}>
                                    {{ $worker->fio }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Add personnel by TN -->
                    <div class="form-group">
                        <h3 for="add_personel_tn">{{ __('Add personnel by TN') }}</h3>
                        <input type="text" id="add_personel_tn" class="form-control" name="add_personel_tn" value="{{ old('add_personel_tn') }}">
                        <button type="button" class="btn btn-primary" onclick="addPersonelByTN()">{{ __('Add') }}</button>
                    </div>
                    <div class="row align-items-center mb-4 p-3 border rounded shadow-sm">
                        <div class="col-md-4">
                            <h5 class="mb-0">{{__('PIB')}}</h5>
                        </div>
                        <div class="col-md-3">
                            <label for="comments">{{__('Comment')}}</label>
                        </div>
                        <div class="col-md-3">
                            <label for="payments">{{__('Payment')}}</label>
                        </div>
                        <div class="col-md-2 d-flex align-items-center">
                            <label class="form-label
                            me-2">{{__('Chief')}}</label>
                        </div>
                    </div>
                    <!-- Chief dropdown -->
                  
                    
                        <div class="container" id="show_workers"></div>
                    
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary w-100">{{ __('Create') }}</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Работы и их описание
    const works_names = @json($DI['works_names']);
    const all_types = @json($DI['all_types']);
    var Type_of_work;

    function Select_type_of_work(work_type_id) {
        // Очистка предыдущих опций
        var select = document.getElementById('Type_of_work');
        select.innerHTML = ''; // Очистка старых значений

        // Получение конечных типов работ по выбранному типу работы
        Type_of_work = works_names[work_type_id];

        // Заполнение выпадающего списка
        for (var finish_type_id in Type_of_work) {
            var finish_type = Type_of_work[finish_type_id];
            var option = document.createElement('option');
            option.value = finish_type_id;  // Устанавливаем ID как значение
            option.text = finish_type.name; // Устанавливаем имя конечного типа работы как текст
            select.appendChild(option);
        }

        // Автоматически показать информацию для первого конечного типа работы, если он есть
        if (select.options.length > 0) {
            DisplayWorkInfo(select.options[0].value);
        }
    }

    function ExtractText(id) {
        let result = [];
        let current_type = all_types.find(x => x.id == id);
        result.push("<b>"+current_type.name + "</b><br> " + current_type.description);
        return result;
    }

    function GenerateTextWithHr(id) {
        let textArray = ExtractText(id);
        
        // Объединяем элементы массива с помощью <hr>
        return textArray.join('<hr>');
    }

    // Функция для отображения информации о выбранной работе
    function DisplayWorkInfo(finish_type_id) {
        var work_info_div = document.getElementById('work_info');
        work_info_div.innerHTML = ''; // Очистка старых значений
        const text = GenerateTextWithHr(finish_type_id);
        work_info_div.innerHTML = text;

    }
    document.addEventListener('DOMContentLoaded', function() {
        // Если есть хотя бы один тип работы
        var first_work_type_id = Object.keys(works_names)[0];
        if (first_work_type_id) {
            Select_type_of_work(first_work_type_id); // Выбираем первый тип работы
        }
    });

        var workers = @json($workers);
        const types_payment = @json($DI['Oplata_pratsi_ids']);
    
        function addPersonelByTN() {
            const tn = document.getElementById('add_personel_tn').value;

            fetch("{{ route('callings.getPersonalForTN') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ tn })
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.length > 0) {
                    workers.push(data[0]);

                    const workersSelect = document.getElementById('workers');
                    const option = document.createElement('option');
                    option.selected = true;
                    option.value = data[0].id;
                    option.text = data[0].fio;
                    workersSelect.appendChild(option);

                    document.getElementById('add_personel_tn').value = '';
                    WListener();
                }
            });
        }

        function WListener() {
    const workersSelect = document.getElementById('workers');
    const showWorkers = document.getElementById('show_workers');

    showWorkers.innerHTML = ''; // Clear existing rows

    Array.from(workersSelect.selectedOptions).forEach(option => {
        const workerId = option.value;
        const workerName = option.text;
        const vyklyk_na_robotu = document.querySelector('input[name="vyklyk_na_robotu"]:checked').value;
        
        // Условное отображение вариантов оплаты
        const paymentOptions = vyklyk_na_robotu != 56
            ? types_payment.map(type => `<option value="${type.id}" ${type.id == 1 ? 'selected' : ''}>${type.name}</option>`).join('')
            : `<option value="${types_payment[0].id}" selected>${types_payment[0].name}</option>`;

            const row = `
    <div class="row align-items-center mb-4 p-3 border rounded shadow-sm" id="worker_${workerId}">
        <div class="col-md-3">
            <h5 class="mb-0">${workerName}</h5>
               <button type="button" class="btn btn-danger" onclick="removeWorker(${workerId})">X</button>
                        
        </div>
        <div class="col-md-4">
            <textarea class="form-control" id="comments_${workerId}" name="comments[${workerId}]" rows="2" placeholder="{{__('Add your comment')}}"></textarea>
        </div>
        <div class="col-md-4">
            <select class="form-select" id="payments_${workerId}" name="payments[${workerId}]">
                ${paymentOptions}
            </select>
        </div>
        <div class="col-md-1 d-flex align-items-center">
            <input type="radio" class="form-check-input" name="chief" value="${workerId}" required>
        </div>
    </div>
    `;

            
            showWorkers.innerHTML += row;
        });
    }


        document.getElementById('workers').addEventListener('change', WListener);

        document.getElementById('start_time').addEventListener('change', (e) => {
            if (e.target.value) {
                document.getElementById('personal_start_id').checked = true;
            }
        });

        document.getElementById('arrival_time').addEventListener('change', (e) => {
            if (e.target.value) {
                document.getElementById('personal_arrival_id').checked = true;
            }
        });

        function parseDateTime(input) {
            return input ? new Date(input) : null;
        }

        document.getElementById('start_time').addEventListener('blur', (e) => {
            const startTime = parseDateTime(document.getElementById('start_time').value);
            const arrivalTime = parseDateTime(document.getElementById('arrival_time').value);

            if (arrivalTime && startTime && arrivalTime > startTime) {
                document.getElementById('arrival_time').value = document.getElementById('start_time').value;
            }
        });
   WListener();
   function removeWorker(workerId) {
    const workerElement = document.getElementById(`worker_${workerId}`);
    workerElement.remove();
    const workersSelect = document.getElementById('workers');
    Array.from(workersSelect.options).find(option => option.value == workerId).selected = false;
}

</script>

@endsection
