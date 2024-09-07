@extends('layouts.app')
@section('content')
<div class="container mt-4 p-3 border rounded bg-light shadow-sm">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
    <div class="row mb-3">
        <div class="col-md-12 text-end">
            <a href="{{ route('master.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('Back') }}</a>
        </div>
    </div>

    <h2 class="text-primary mb-4">{{ __('Briefing') }}</h2>

    @php $brief_ex = 0; @endphp
    @foreach ($master->personals as $personal)
        @if($master->briefing && $personal->briefings()->where('briefing_id', $master->briefing->id)->exists())
            <!-- Случай, если брифинг существует у персонала -->
            <div class="card mb-3"  id="rwr_{{ $personal->id }}">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-success">{{ $personal->fio }}</h4>
                    <span class="badge bg-success">{{ __('Brief given') }}</span>
                </div>
            </div>
            @else
            <div class="card mb-3"  id="rwr_{{ $personal->id }}"></div>
            <!-- Случай, если брифинг отсутствует у персонала -->
            <form method="POST" action="{{ route('masterbriefing') }}" class="card mb-3" >

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">{{ $personal->fio }}</h4>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="master_id" value="{{ $master->id }}">
                    <input type="hidden" name="personal_id" value="{{ $personal->id }}">
                    <div class="card mb-2"  id="rwr_{{ $personal->id }}"></div>
                    <div class="input-group mb-3" id="worker_{{ $personal->id }}">
                        <span class="input-group-text">{{ __('Tab num') }}</span>
                        <input type="number" class="form-control" id="tn" name="tn" placeholder="{{ __('Enter tab number') }}">
                        <button type="submit" class="btn btn-primary">{{ __('Give') }}</button>
                    </div>
                </div>
            </form>
            @php $brief_ex = 1; @endphp
        @endif
    @endforeach

    @if($brief_ex == 0)
        <div class="row mb-4">
            <div class="col-md-12 text-center">
                <a href="{{ route('master_running', ['mi' => $master->id]) }}" class="btn btn-info">{{ __('Beginning') }}</a>
            </div>
        </div>
    @else
    <div class="row  mb-4 @if($brief_ex == 1) d-none @endif">
        <div class="col-md-12 text-center">
            
            <a href="{{route('master_running',['mi'=>$master->id])}}" class="btn btn-info" >{{__('Begining')}}</a>
        </div>
    </div>    
    @endif

    <!-- Task Information -->
    <div class="card p-3">
        <h3 class="text-primary">{{ __('Task') }}: <span class="text-dark">{{ $master->text }}</span></h3>
        @php 
            $color = $master->urgency > 5 ? 'danger' : ($master->urgency > 3 ? 'warning' : 'success');
            $urgency_label = $master->urgency > 5 ? __('High') : ($master->urgency > 3 ? __('Medium') : __('Low'));
        @endphp
        <h3>{{ __('Urgency') }}: <span class="badge bg-{{ $color }}">{{ $urgency_label }}</span> - <small>{{ $master->deadline }}</small></h3>
        <h3>{{ __('Basis') }}: <span class="text-muted">{{ $master->basis }}</span></h3>
        <h3>{{ __('Who gave the task') }}: <span class="text-muted">{{ $master->who }}</span></h3>
        <h3>{{ __('Comment') }}: <span class="text-muted">{{ $master->comment }}</span></h3>
    </div>
</div>

    <script>
  // Обработчик для всех форм на странице
  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        e.preventDefault(); // Отменяем стандартное поведение формы
        
        const url = form.action;
        const data = new URLSearchParams(new FormData(form));
        
        fetch(url, {
            method: 'POST',
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
            .then((response) => {
                // Проверка статуса ответа
                if (!response.ok) {
                    throw new Error('Network response was not ok ' + response.statusText);
                }
                return response.json();
            })
            .then((data) => {
                console.log('Ответ сервера:', data);
                if (data.status === 'success') {
                    // Находим элемент и обновляем его содержимое
                    const personalRow = document.getElementById('rwr_' + data.worker_id);
                    if (personalRow) {
                        personalRow.innerHTML += `<div class="col-md-6 bg-success"><h3>{{__('Brief given')}}</h3></div>`;
                    }

                    // Удаляем элемент после обновления
                    const workerDiv = document.getElementById('worker_' + data.worker_id);
                    if (workerDiv) {
                        workerDiv.remove();
                    }

                    // Проверяем наличие оставшихся элементов
                    checkAndShow();
                } else {
                    alert("{{__('Your request failed')}}");
                    form.querySelector('input[type="number"]').value = ''; // Очищаем поле ввода
                }
            })
            .catch((error) => {
                console.error('Ошибка при выполнении запроса:', error);
                alert("{{__('An error occurred while processing your request')}}");
            });
    });
});

// Функция для проверки наличия элементов и отображения другого блока
function checkAndShow() {
    const workers = document.querySelectorAll('[id^="worker_"]');
    if (workers.length === 0) {
        const hiddenBlock = document.querySelector('.row.d-none');
        if (hiddenBlock) {
            hiddenBlock.classList.remove('d-none');
        }
    }
}

      /**/
    </script>
@endsection