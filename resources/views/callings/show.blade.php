@extends('layouts.app')

@section('content')
@php
use Carbon\Carbon;
$CallingType = null;
$ParentType = null;

if ($calling->type_id != null) {
    $CallingType = \App\Models\Type::find($calling->type_id);
    $ParentType = $CallingType ? \App\Models\Type::find($CallingType->parent_id) : null;
} else {
    $CallingType = \App\Models\Type::where('slug', 'Oplata-pratsi')->first();
    $ParentType = $CallingType ? \App\Models\Type::find($CallingType->parent_id) : null;
}
@endphp
    <div class="container">
        <!-- Ошибки и успешные сообщения -->
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

        <!-- Заголовок и кнопка возврата -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>{{ __('Calling Details') }}</h1>
                <a class="btn btn-light w-100 mb-3" href="{{ route('callings.index') }}">
                    {{ __('Back') }}
                </a>
            </div>
        </div>

      <!-- Карточка вызова -->
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4>{{ __('Calling Information') }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <strong>{{ __('Description') }}:</strong>
                                    <p>{{ $calling->description }}</p>
                                </div>
                                <div class="mb-3">
                                    <div class="container" id="work_info">
                                        <b>{{ $CallingType->name }}</b>
                                        <br>
                                        {{ $CallingType->description }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <strong>{{ __('Arrival Time') }}</strong>
                                    <p>{{ date('Y-m-d H:i', strtotime($calling->arrival_time)) }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('Start Time') }}</strong>
                                    <p>{{ date('Y-m-d H:i', strtotime($calling->start_time)) }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>{{ __('End Time') }}</strong>
                                    <p>{{ date('Y-m-d H:i', strtotime($calling->end_time)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <strong>{{ __('Working Time') }}</strong>
                                <p class="font-weight-bold">
                                    @if ($calling->start_time && $calling->end_time)
                                        @php
                                            // Преобразуем строки в объекты Carbon
                                            $start = Carbon::parse($calling->start_time);
                                            $end = Carbon::parse($calling->end_time);
                                            
                                            // Вычисляем общее время работы
                                            $workingHours = $start->diff($end);
                                            $totalMinutes = $workingHours->h * 60 + $workingHours->i;

                                            // Проверяем, нужно ли вычитать перерыв
                                            $shouldDeductBreak = $totalMinutes > 360; // Более 6 часов
                                            $isNightShift = $start->hour >= 22 || $end->hour < 6;

                                            if ($shouldDeductBreak && !$isNightShift) {
                                                $totalMinutes -= 30; // Вычитаем 30 минут перерыва
                                            }

                                            // Преобразуем обратно в часы и минуты
                                            $finalHours = floor($totalMinutes / 60);
                                            $finalMinutes = $totalMinutes % 60;
                                        @endphp

                                        {{ sprintf('%02d:%02d', $finalHours, $finalMinutes) }}
                                    @else
                                        <span class="text-danger">{{ __('Not available') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>


        <!-- Карточка с информацией о рабочих -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-secondary text-white">
                        <h4>{{ __('Assigned Workers') }}</h4>
                    </div>
                    <div class="card-body">
                        @if($workers->isEmpty())
                            <p>{{ __('No workers assigned to this calling.') }}</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach ($workers as $worker)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong></strong> {{ $worker->fio }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <strong> {{ $DI['all_types'][$worker->pivot->worker_type_id]->name }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ $DI['all_types'][$worker->pivot->payment_type_id]->name }}</strong> 
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            
                                            @if ($worker->pivot->comment)
                                                 <p><strong>{{ __('Comment:') }}</strong> {{ $worker->pivot->comment }}</p>
                                            @endif
                                        

                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Карточка с информацией о чекинах -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h4>{{ __('Checkins Information') }}</h4>
                    </div>
                    <div class="card-body">
                        @if($checkins->isEmpty())
                            <p>{{ __('No checkins available.') }}</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach ($checkins as $checkin)
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>{{ __('Name:') }}</strong> {{ $checkin->fio }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('Checkin Type:') }}</strong> {{ $checkin->pivot->checkin_type_id }}
                                            </div>
                                            <div class="col-md-3">
                                                <strong>{{ __('Type:') }}</strong> {{ $checkin->pivot->type }}
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <strong>{{ __('Comment:') }}</strong>
                                            <p>{{ $checkin->pivot->comment ?? __('No comment provided') }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
              <button class="btn btn-success w-100" onclick="ShowModalWin()">{{__('Confirm')}} / {{__('Reject')}} </button>
                        </div>
        </div>
    </div>

    
<div class="modal" id="modalWin">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Boss will Confirm this work')}}</h5>
                <button onclick="hideModalWin()" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('callings.confirmSS')}}" method="POST">
                    @csrf
                    <input type="hidden" name="calling_id" id="calling_id"  value="{{$calling->id}}">
                    <input type="hidden" name="checkin_type_id" id="checkin_type_id" value= "77">
                    <div class="form-group">
                        <label for="number">{{__('Number of people')}}</label>
                        <span id="number">
                            {{$calling->number}}
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <span id="description">
                            {{$calling->description}}
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="workers">
                            {{__('Workers')}}
                        </label>
                        <ul id="workers">
                            @foreach ($workers as $worker)
                                <li>
                                    <strong>{{$worker->fio}}</strong>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="start">
                            {{__('Start')}}
                        </label>
                        <span id="start_show_time">
                            {{date('Y-m-d H:i', strtotime($calling->arrival_time))}}
                        </span>
                    </div>
                    <div class="form-group">
                        <label for="in_work">
                            {{__('In work')}}
                        </label>
                        <span id="in_work_show_time">
                            {{date('Y-m-d H:i', strtotime($calling->start_time))}}
                        </span>
                     
                    </div>
                    <div class="form-group">
                        <label for="completed">
                            {{__('Completed')}}
                        </label>
                        <span id="completed_show_time">
                            {{date('Y-m-d H:i', strtotime($calling->end))}}
                            </span>                        
                    </div> 
         
                    <div class="form-group">
                        <label for="comment">{{__('Comment')}}</label>
                        <textarea name="comment" id="comment" class="form-control"
                        onchange="document.getElementById('comment_reject').value = this.value"
                        ></textarea>
                    </div> 
                    <div class="row">
                        <div class="col-md-6">
                          <button class="btn btn-success w-100">{{__('Confirm')}}</button>
                  
                        </form>  
                        </div>
                        <div class="col-md-6">
                           <form action="{{route('callings.rejectSS')}}" method="POST" >  
                                @csrf
                                <input type="hidden" name="comment" id="comment_reject" value="">
                                <input type="hidden" name="calling_id" id="calling_idrj"  value="{{$calling->id}}">
                                <input type="hidden" name="checkin_type_id" id="checkin_type_id" value="78" >
                                <button type="submit"  class="btn btn-danger w-100">{{__('Reject')}}</button>
                            </form> 
                        </div>
                        
                    </div>      

                    
            

            </div>
        </div>
    </div>
</div>
<script>

    function hideModalWin() {
    $('#modalWin').modal('hide');
    }

    function ShowModalWin() {
        $('#modalWin').modal('show');
    }

    // change textareas value to hidden input value
    document.getElementById('comment').onchange = function() {
        document.getElementById('comment_reject').value = this.value;
    }


</script>
@endsection
