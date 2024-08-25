@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
               
                <a class="text-right" href="{{ route('master.index') }}">{{__('Back')}}</a>
                
                <h2>{{__('Briafing')}}</h2>
                @foreach ($master->personals as $personal)
                 **{{$master->briafing}} **
                <form method="POST" action="{{ route('masterbriefing') }}">
                    <div class="row">
                        <div class="col-md-6">
                            <h3> {{ $personal->fio }}</h3>
                        </div>
                        <div class="col-md-6" id="worker_{{ $personal->id }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="master_id" value="{{ $master->id }}">
                            <input type="hidden" name="personal_id" value="{{ $personal->id }}">
                            <div class="form-group">
                                <label for="tn">{{__('Tab num')}}</label>
                                <input type="number" class="form-control" id="tn" name="tn">
                                <button type="submit" class="btn btn-primary">{{__('Give')}}</button>
                            </div>
                        </div>
                    </div>                   
                </form>
            @endforeach
                <h3>{{__('Task')}}: {{ $master->text }}</h3>
                @php $color = $master->urgency > 5 ? 'red' : ($master->urgency > 3 ? 'orange' : 'green') @endphp
                <h3>{{__('Urgency')}}: <span style="color: {{ $color }}">{{ $master->deadline }}</span></h3>
                <h3>{{__('Basis')}}: {{ $master->basis }}</h3>
                <h3>{{__('Who give task')}}: {{ $master->who }}</h3>
                <h3>{{__('Comment')}}: {{ $master->comment }}</h3>
                
                    
            </div>
        </div>
    </div>
    <script>
  // Обработчик для всех форм на странице

  document.querySelectorAll('form').forEach(function(form) {
           form.addEventListener('submit', function(e) {
               e.preventDefault(); // Отменяем стандартное поведение формы
               
               var url = form.action;        
               var data = new URLSearchParams(new FormData(form)); 
               // Преобразуем данные формы в формат URLSearchParams    
               fetch(url, {
                   method: 'POST',
                   body: data,
                   headers: {
                       'Content-Type': 'application/x-www-form-urlencoded', // Устанавливаем тип содержимого
                   }
               })
               .then(response => response.json()) // Преобразуем ответ в JSON
               .then(data => {
                   if (data.success) {
                       document.getElementById('worker_' + data.personal_id).innerHTML = '<h3>{{__('Task given')}}</h3>';
                   } else {
                       alert(data.error);
                   }                  

                });
           });
       });
    </script>
@endsection