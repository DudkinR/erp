@extends('layouts.app')
@section('content')
@php                     
$workers = $personnelInSameDivisions ? $personnelInSameDivisions : [];
$alarm_position=['керевник','начальник','руководитель','директор'];
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
            <form action="{{ route('callings.update', $calling) }}" method="POST" enctype="multipart/form-data">      @csrf
                @method('PUT')
                <!-- Form section -->
                <div class="container">
                    <div class="row">
                        <!-- Первый столбец -->
                        <div class="col-md-6">
                            <div class="form-section">
                               <!-- Поле выбора вызова на работу -->
                               <div class="form-group mb-3">
                                <h2>{{ __('Call to Work Type') }}</h2>
                                @php
                                $CallingType = null;
                                $ParentType = null;
                                $Vyklyk =0; 
                                if ($calling->type_id != null) {
                                    $CallingType = \App\Models\Type::find($calling->type_id);
                                    $ParentType = $CallingType ? \App\Models\Type::find($CallingType->parent_id) : null;
                                } else {
                                    $CallingType = \App\Models\Type::where('slug', 'Oplata-pratsi')->first();
                                    $ParentType = $CallingType ? \App\Models\Type::find($CallingType->parent_id) : null;
                                }
                                @endphp
                            
                                @foreach($DI['Vyklyk_na_robotu_ids'] as $Vyklyk_na_robotu_id)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="vyklyk_na_robotu_{{ $Vyklyk_na_robotu_id->id }}" name="vyklyk_na_robotu"                                    
                                        value="{{ $Vyklyk_na_robotu_id->id }}" 
                                        onclick="Select_type_of_work({{ $Vyklyk}})"
                                        @if($ParentType && $ParentType->id == $Vyklyk_na_robotu_id->id) checked @endif>
                            
                                    <label class="form-check-label" for="vyklyk_na_robotu_{{ $Vyklyk_na_robotu_id->id }}">
                                        {{ __($Vyklyk_na_robotu_id->name) }}
                                        @if($Vyklyk > 0) 
                                            <input type="hidden" id="nedoruchni" value="{{ $Vyklyk_na_robotu_id->id }}">
                                            @endif
                                    </label>
                                </div>
                                @php $Vyklyk ++; @endphp
                                @endforeach
                            </div>
                            
                
                               
                            </div>
                        </div>
                
                        <!-- Второй столбец -->
                        <div class="col-md-6">
                            <div class="form-section"> <!-- Arrival time (date-time input) -->
                                <div class="form-group mb-3">
                                    <h2 for="arrival_time">{{ __('Arrival Time') }}</h2>
                                    <input type="datetime-local" id="arrival_time" class="form-control" name="arrival_time"
                                    @if($calling->arrival_time==null) value= "" style="background-color: yellow"
                                    @else
                                     value= "{{ date('Y-m-d\TH:i', strtotime($calling->arrival_time)) }}" 
                                    @endif
                                    required>
                                </div>
                                <!-- Start time (date-time input) -->
                                <div class="form-group mb-3">
                                    <h2 for="start_time" title="{{ __('Go to KPP') }}">{{ __('Start Time') }}</h2>
                                    <input type="datetime-local" id="start_time" class="form-control" name="start_time"
                                    @if($calling->start_time==null) value= "" style="background-color: yellow"

                                    @else
                                        value= "{{ date('Y-m-d\TH:i', strtotime($calling->start_time)) }}" 
                                    @endif

                                required>
                                </div>
                
                                <!-- End time (date-time input) -->
                                <div class="form-group mb-3">
                                    <h2 for="end_time">{{ __('End Time') }}</h2>
                                    <input type="datetime-local" id="end_time" class="form-control" name="end_time"
                                    @if($calling->end_time==null) value= "" style="background-color: yellow"
                                    @else
                                        value= "{{ date('Y-m-d\TH:i', strtotime($calling->end_time)) }}" 
                                    @endif
                                    required>
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
                        >{{ $calling->description   }}</textarea>
                    </div>
                </div>
               <!-- Поле выбора типа работы -->
               <div class="form-section">
                <div class="form-group">
                    <h2>{{ __('Type of work') }}:</h2>
                    <select id="Type_of_work" class="form-control" name="Type_of_work" size=3 onchange="DisplayWorkInfo(this.value)" required>
                        @if($ParentType)
                        @foreach(array_filter($DI['all_types'], fn($type) => $type['parent_id'] == $ParentType->id) as $type)
                            <option value="{{ $type['id'] }}" {{ $type['id'] == $calling->type_id ? 'selected' : '' }}>
                                {{ $type['name'] }}
                            </option>
                        @endforeach

                        @else
                            <option value="" disabled>{{ __('No work types available') }}</option>
                        @endif
                    </select>
                    <input type="hidden" name="type_id_before" value="{{ $calling->type_id }}">
                </div>
            </div>
            
            

                <!-- Поле для отображения описания -->
                <div class="form-section">
                    <div class="container" id="work_info" >
                        <b>{{optional($CallingType)->name}}</b>
                        <br>
                        {{optional($CallingType)->description}}
                    </div>
                </div>
                

                <!-- Workers multi-select -->
                <div class="form-section">    

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
                        <div class="container" id="show_workers">
                            @php   $Kerivnyk_bryhady = App\Models\Type::where('slug', 'Kerivnyk-bryhady')->first(); @endphp
                            @foreach($calling->workers as $worker)
                            @php 
                                $isAlarm = false; 
                                
                                foreach ($alarm_position as $word) {
                               
                                    if (stripos($worker->positions[0]['name'], $word) !== false) {
                                        $isAlarm = true;
                               
                                        break;
                                    }
                                }  
                                                  
                            @endphp 
                                <div class="row align-items-center mb-4 p-3 border rounded shadow-sm" id = "worker_{{ $worker->id }}">
                                    <div class="col-md-3">
                                        <h5 class="mb-0">{{ $worker->fio }}</h5>
                                        <h6 class="mb-0  @if($isAlarm) ? 'bg-warning' : 'bg-light' @endif">{{$worker->positions[0]['name'] }}</h6>
                                        <button type="button" class="btn btn-danger" onclick="removeWorker({{ $worker->id }})">X</button>
                                    </div>
                                    <div class="col-md-4">
                                        <textarea class="form-control" id="comments_{{ $worker->id }}" name="comments[{{ $worker->id }}]" rows="2" placeholder="{{__('Add your comment')}}">{{ $worker->pivot->comments }}</textarea>
                                        <label for="start_time_{{ $worker->id }}">{{__('start time')}}</label>
                                        {{__('start_time')}}<input type="datetime-local" id="start_time_{{ $worker->id }}" class="form-control" name="start_time[{{ $worker->id }}]" value=" {{ $worker->pivot->start_time }}">
                                        <label for="end_time_{{ $worker->id }}">{{__('end time')}}</label>
                                        {{__('end_time')}}<input type="datetime-local" id="end_time_{{ $worker->id }}" class="form-control" name="end_time[{{ $worker->id }}]" value="{{ $worker->pivot->end_time }}">                             
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" id="payments_{{ $worker->id }}" name="payments[{{ $worker->id }}]" required>
                                            @foreach($DI['Oplata_pratsi_ids'] as $type)
                                                <option value="{{ $type->id }}" {{ $type->id == $worker->pivot->payment_type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center">
                                        <input type="radio" class="form-check-input" name="worker_type_id" value="{{ $worker->id }}" {{ $worker->pivot->worker_type_id == $Kerivnyk_bryhady->id ? 'checked' : '' }} required>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Add picture with signatures -->
                        <div class="form-group"> 
                            <a href="{{route('callings.print',$calling)}}" class="btn btn-success w-100" target="_blank" >{{__('Print')}}</a>
                            <!-- if has picture with signatures  show-->
                            @if($calling->picture)
                                <img src="{{ asset($calling->picture) }}" alt="{{__('Picture with signatures')}}" class="img-fluid">
                                <h3 for="add_picture">{{ __('Update picture with signatures') }}</h3>    
                            @else
                            <h3 for="add_picture">{{ __('Add picture with signatures') }}</h3>
                            @endif
                            <input type="file" id="add_picture" class="form-control" name="add_picture" accept="image/*">
                        </div>
                        <!-- Add personnel by TN -->
                        <div class="form-group">
                            <h3 for="add_personel_tn">{{ __('Add personnel by TN') }}</h3>
                            <input type="text" id="add_personel_tn" class="form-control" name="add_personel_tn" value="{{ old('add_personel_tn') }}">
                            <button type="button" class="btn btn-primary" onclick="addPersonelByTN()">{{ __('Add') }}</button>
                        </div>
                </div>
           
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary w-100">{{ __('Update') }}</button>
            </form>
        </div>
    </div>
</div>

<script>
        // Работы и их описание
        const works_names = @json($DI['works_names']);
        const all_types = @json($DI['all_types']);
        const alarm_position =@json($alarm_position);
        var workers = @json($calling->workers);
        const  types_payment = Object.values(@json($DI['Oplata_pratsi_ids']));
        console.log(types_payment);
       // console.log(all_types[1].name);
        var Type_of_work;
        function Select_type_of_work(work_type_id) {
            if (!works_names[work_type_id]) {
            console.error("Невірний ID типу роботи:", work_type_id);
            return;
            }
            // Очистка предыдущих опций
            var select = document.getElementById('Type_of_work');
            select.innerHTML = ''; // Очистка старых значений
            const type_id_before = document.querySelector('input[name="type_id_before"]').value;
            var info_select = 0;   

            // Получение конечных типов работ по выбранному типу работы
            var Type_of_work = works_names[work_type_id];

            // Заполнение выпадающего списка
            for (var finish_type_id in Type_of_work) {
                var finish_type = Type_of_work[finish_type_id];
                var option = document.createElement('option');
                option.value = finish_type_id;  // Устанавливаем ID как значение
                option.text = finish_type.name; // Устанавливаем имя конечного типа работы как текст
                if (finish_type_id == type_id_before) {
                    option.selected = true;
                    DisplayWorkInfo(type_id_before); // Показ информации для выбранного типа работы
                    info_select = 1;
                }
                select.appendChild(option);
    }

    // Автоматически показать информацию для первого конечного типа работы, если он есть
    if (select.options.length > 0 && info_select == 0) {
        DisplayWorkInfo(select.options[0].value); // Показ информации для первого типа работы
    }
}

function ExtractText(id) {
    let result = [];
    let current_type = all_types.find(x => x.id == id);
    result.push("<b>" + current_type.name + "</b><br> " + current_type.description);
    return result;
}

// Функция для генерации HTML с <hr> разделителем
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

// Убираем повторный вызов Select_type_of_work из DisplayWorkInfo



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
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then(data => {
            if (data && Array.isArray(data) && data.length > 0 && data[0] && typeof data[0] === 'object') {
 
                let worker = data[0];

                // Добавляем поле "pivot" с нужными значениями
                worker.pivot = {
                    calling_id: 9, // можно динамически передавать значение
                    personal_id: worker.id,
                    worker_type_id: null, // или заменить на значение по умолчанию
                    payment_type_id: null, // или заменить на значение по умолчанию
                    comment: null
                };

                // Добавляем работника в массив workers
                workers.push(worker);

                // Очищаем поле ввода
                document.getElementById('add_personel_tn').value = '';

                // Обновляем отображение списка работников
                WListener();
            } else {
        console.error("Невірний формат даних або пустий результат:", data);
                }
            })
            .catch(error => {
                console.error("Помилка при отриманні даних:", error);
            });
        }


            var kerevnik_bryhady = {{$Kerivnyk_bryhady->id}}; 
            function ReadValWorkers() {
                var workersSelect = document.getElementById('workers');
                var old_values = [];
                Array.from(workersSelect.selectedOptions).forEach(option => {
                    old_values.push(option.value);
                });
                return old_values;
            }

            function WListener() {
                if (!Array.isArray(types_payment)) {
                    console.error("types_payment не є масивом або не визначений");
                    return;
                }
                const showWorkers = document.getElementById('show_workers');
                showWorkers.innerHTML = ''; // Очищаем существующие строки
               // val OldValWorkers = ReadValWorkers();
                // Проверяем, есть ли рабочие в массиве
                if (!workers || workers.length === 0) {
                    showWorkers.innerHTML = '<p>No workers available</p>'; // Сообщение, если список пуст
                    return;
                }

                workers.forEach(worker => {
                    const workerId = worker.id;
                    const workerName = worker.fio;
                    const positionName = worker.positions && worker.positions.length > 0 ? worker.positions[0].name : 'No Position';

                    const vyklyk_na_robotu = document.querySelector('input[name="vyklyk_na_robotu"]:checked');
                    const vyklykValue = vyklyk_na_robotu ? vyklyk_na_robotu.value : null; // Проверка наличия выбранного элемента

                    const payment_type_id = worker.pivot.payment_type_id;
                    const worker_type_id = worker.pivot.worker_type_id;
                    const comments = worker.pivot.comments ? worker.pivot.comments : ''; // По умолчанию пустая строка, если не указано

                    // Генерация вариантов оплаты в зависимости от условия
                    const paymentOptions = vyklykValue != document.getElementById('nedoruchni').value
                        ? types_payment.map(type =>
                            `<option value="${type.id}" ${type.id == payment_type_id ? 'selected' : ''}>${type.name}</option>`
                        ).join('') // Собираем все в строку
                        : `<option value="" disabled selected>Not available</option>`; // Значение по умолчанию, если `vyklykValue == document.getElementById('nedoruchni').value
                        const isAlarm = alarm_position.some(word => positionName.toLowerCase().includes(word.toLowerCase()));
                    console.log(isAlarm);
                    // Формируем HTML строки
                    const row = `
                        <div class="row align-items-center mb-4 p-3 border rounded shadow-sm" id="worker_${workerId}">
                            <div class="col-md-3">
                                <h5 class="mb-0">${workerName}</h5>
                                 <p class="text-muted mb-1 ${isAlarm ? 'bg-warning' : 'bg-light'}">${positionName}</p> <!-- Должность работника -->
              
                                <button type="button" class="btn btn-danger" onclick="removeWorker(${workerId})">X</button>
                            </div>
                            <div class="col-md-4">
                                <textarea class="form-control" id="comments_${workerId}" name="comments[${workerId}]" rows="2" placeholder="Add your comment">${worker.pivot.comment ? worker.pivot.comment : ''}</textarea>
                                <label for="start_time_${workerId}" title="{{ __('Go to KPP') }}">{{__('Start Time')}}</label>
                                <input type="datetime-local" id="start_timew_${workerId}" class="form-control" name="start_timew[${workerId}]" value="${worker.pivot.start_time}">
                                <label for="end_time_${workerId}">{{__('End Time')}}</label>
                                <input type="datetime-local" id="end_timew_${workerId}" class="form-control" name="end_timew[${workerId}]" value="${worker.pivot.end_time}">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select" id="payments_${workerId}" name="payments[${workerId}]" >
                                    ${paymentOptions}
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <input type="radio" class="form-check-input" name="chief" value="${workerId}"
                                    ${worker_type_id == kerevnik_bryhady ? 'checked' : ''}
                                    required>
                            </div>
                        </div>
                    `;

                    // Добавляем строку к HTML
                    showWorkers.innerHTML += row;
                });
            }





            
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

            document.getElementById('start_time').addEventListener('blur', () => {
                const startTime = parseDateTime(document.getElementById('start_time').value);
                const arrivalTime = parseDateTime(document.getElementById('arrival_time').value);

                if (startTime && (!arrivalTime || arrivalTime < startTime)) {
                    document.getElementById('arrival_time').value = document.getElementById('start_time').value;
                }
            });

        WListener();
        function removeWorker(workerId) {
            document.getElementById(`worker_${workerId}`).remove(); // Приховуємо рядок перед оновленням списку
            workers = workers.filter(worker => worker.id !== workerId);
            WListener();
        }
</script>

@endsection
