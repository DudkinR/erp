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
            <input type="text" id="search" class="form-control" placeholder="{{__('Search')}}">
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
            <h3>{{__('Form of callings')}}</h3>
            <h1>{{__('Admin')}}</h1>
            </div>
        </div>  
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-warning w-100" href="{{ route('callings.create') }}">{{__('New')}}</a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-info w-100" onclick="$('#modalReserve').modal('show')">{{__('Reserve')}}</a>
            </div>
        </div>  
        <div class="row">
            <div class="col-md-6">
                <a href="{{route('callings.printOrder')}}" class="btn btn-light w-100">{{__('Print')}}</a>

            </div>
            <div class="col-md-6">
  
                <form action="/Icallings" method="post" class="form-inline" onsubmit="return validateFilters()">
                    @csrf
                    <select name="filter" class="form-control">
                        <option value="">{{ __('All') }}</option>
                        <option value="today" @if(($filter ?? '') == 'today') selected @endif>{{ __('Today') }}</option>
                        <option value="week" @if(($filter ?? '') == 'week') selected @endif>{{ __('Week') }}</option>
                        <option value="month" @if(($filter ?? '') == 'month') selected @endif>{{ __('Month') }}</option>
                        <option value="in_sup" @if(($filter ?? '') == 'in_sup') selected @endif>{{ __('In supervisor') }}</option>
                        <option value="in_work" @if(($filter ?? '') == 'in_work') selected @endif>{{ __('In work') }}</option>
                        <option value="not_started" @if(($filter ?? '') == 'not_started') selected @endif>{{ __('Not started') }}</option>
                        <option value="completed" @if(($filter ?? '') == 'completed') selected @endif>{{ __('Completed') }}</option>
                        <option value="in_boss" @if(($filter ?? '') == 'in_boss') selected @endif>{{ __('In boss') }}</option>
                        <option value="in_svn" @if(($filter ?? '') == 'in_svn') selected @endif>{{ __('In SVN') }}</option>
                        <option value="in_profcom" @if(($filter ?? '') == 'in_profcom') selected @endif>{{ __('In profcom') }}</option>
                        <option value="in_vonop" @if(($filter ?? '') == 'in_vonop') selected @endif>{{ __('In vonop') }}</option>
                    </select>
                    <button type="submit" class="btn btn-success">{{ __('Filter') }}</button>  

                    <script>
                        function validateFilters() {
                            if (document.querySelector('select[name="filter"]').value == '') {
                                alert('{{ __("Choose filter") }}');
                                return false;
                            }
                            return true;
                        }
                    </script>
                </form>
                
            </div>
            
        </div>
        <div class="container">
            <table class="table table-striped" id="callingsTable">
                <thead>
                    <tr>
                        <th>{{__('№')}}</th>
                        <th>{{__('Department')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Start data')}}</th>
                        <th>{{__('Hours')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($callings as $calling)
                    <tr>
                        @php 
                            if($calling->status == 'for_print'){
                                $color_td = 'bg-info';
                                $text_td = 'For print';
                            }elseif($calling->status == 'workshop-chief'){
                                $color_td = 'bg-light';
                                $text_td = 'Workshop chief';
                            }
                            elseif($calling->status == 'boss'){
                                $color_td = 'bg-light';
                                $text_td = 'Boss';
                            }
                            elseif($calling->status == 'SVNtaPB'){
                                $color_td = 'bg-light';
                                $text_td = 'SVNtaPB';
                            }
                            elseif($calling->status == 'VONtaOP'){
                                $color_td = 'bg-success';
                                $text_td = 'VONtaOP';
                            }
                            elseif($calling->status == 'profcom'){
                                $color_td = 'bg-light';
                                $text_td =  'Profcom';
                            }
                            elseif($calling->status == 'created'){
                                $color_td = 'bg-warning';
                                $text_td = 'Created';
                            }elseif($calling->status == 'supervision'){
                                $color_td = 'bg-warning';
                                $text_td = 'Supervision';
                            }
                            else{
                                $color_td = 'bg-danger';
                                $text_td = 'Not found';
                            }
                        @endphp
                        <td class="{{$color_td}}">
                           № {{$calling->id}}
                           <hr>
                           {{$text_td}}
                        </td>
                        <td>
                            @php $mass_divisions = []; @endphp

                            @foreach($calling->workers as $worker)
                                @if(!empty($worker->divisions) && isset($worker->divisions[0]->name) && $worker->pivot->worker_type_id == 6)
                                    {{ $worker->divisions[0]->name }} <br>
                                    
                                    @php 
                                        $fio = explode(" ", $worker->fio); 
                                        $fn = $fio[0]; 
                                    @endphp
                                    
                                    <b>{{ $fn }}</b><br>
                                    <u>{{ $worker->phone }}</u>
                                @endif
                                
                                @php
                                    if (!empty($worker->divisions) && isset($worker->divisions[0]->name)) {
                                        if (!isset($mass_divisions[$worker->divisions[0]->name])) {
                                            $mass_divisions[$worker->divisions[0]->name] = [];
                                        }
                                        $mass_divisions[$worker->divisions[0]->name][] = $worker->fio;
                                    }
                                @endphp
                            @endforeach
                            
                        </td>
                        <td>
                            <p style = "font-size: 20px;">
                            {{$calling->description}}
                            </p>
                            <br>
                            <ul>
                            @foreach($calling->workers as $worker)                               
                            @php 
                            $isAlarm = false;                                         
                                        foreach ($alarm_position as $word) {                                    
                                            if (stripos($worker->positions[0]['name'], $word) !== false) {
                                                $isAlarm = true;                                    
                                                break;
                                            }
                                        }  
                                $start = \Carbon\Carbon::parse($worker->pivot->start_time);
                                $end = \Carbon\Carbon::parse($worker->pivot->end_time);
                                $diffInMinutes = $start->diffInMinutes($end); // Total difference in minutes
                                $hours = floor($diffInMinutes / 60); // Get the number of hours
                                $minutes = $diffInMinutes % 60;      // Get the remaining minutes             
                                    @endphp                               
                                    <li class="{{ $isAlarm ? 'bg-warning' : 'bg-light' }}">
                                        <b>{{ $worker->fio }}</b>
                                        ({{ $worker->positions[0]->name }})    
                                        <b>  {{ sprintf('%02d', $hours) }}:{{ sprintf('%02d', $minutes) }}  </b>   
                                    </li> 
                                    
                               
                            @endforeach</ul>
                        </td>
                        <td>
                                                                             
                            {{ \Carbon\Carbon::parse($calling->start_time)->format('d.m.Y ') }} 
                        </td>
                        <td>
                        @php
                                $start = \Carbon\Carbon::parse($calling->start_time);
                                $end = \Carbon\Carbon::parse($calling->end_time);
                                $diffInMinutes = $start->diffInMinutes($end); // Total difference in minutes
                                $hours = floor($diffInMinutes / 60); // Get the number of hours
                                $minutes = $diffInMinutes % 60;      // Get the remaining minutes
                            @endphp

                            {{ sprintf('%02d', $hours) }}:{{ sprintf('%02d', $minutes) }}   </td>
                            

                          
                        <td >
                            @if($color_td =='bg-success')
                            <button onclick="ShowModalWin({{$calling->id}})" class="btn btn-success w-100">{{__('Confirm')}}</button>
                            @endif
                            <a href="{{route('callings.show',$calling)}}"" class="btn btn-warning w-100"> {{__('Show')}} </a>
                         </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        
        </div>
    </div>
</div><div class="modal" id="modalReserve">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('Reserve form blank')}}</h5>
                <button onclick="hideModalReserve()" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{route('callings.reserveStore')}}" method="POST">
                    @csrf
                    <input type="hidden" name="calling_id" id="calling_id_reserve">
                    <div class="form-group">
                        <label for="tab_number">{{__('Tab Number of people')}}</label>
                        <input type="number" name="tab_number" id="tab_number" class="form-control"                         
                        onblur="WhatPersonelByTN()" required>
                        <div id=show_personel></div>

                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description of work')}}</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="type_of_work">{{__('Type of work')}}</label>
                        <select name="type_of_work" id="type_of_work" class="form-control" required>
                            @foreach($DI['Vyklyk_na_robotu_ids'] as $Vyklyk_na_robotu_id)
                            <optgroup label="{{$Vyklyk_na_robotu_id->name}}">
                                @foreach($DI['works_names'] as $parent_id=>$work_type)
                                 @if( $Vyklyk_na_robotu_id->id == $parent_id)
                                  @foreach($work_type as $key=>$work) 
                                  <option value="{{$key}}">
                                    {{$work['name']}}
                                    </option>
                                  @endforeach
                                 @endif                                     
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-success">{{__('Reserve')}}</button>
                </form>
            </div>
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
                    <input type="hidden" name="calling_id" id="calling_id" >
                    <input type="hidden" name="tp_check" value="VONtaOP">
                    <input type="hidden" name="checkin_type_id" id="checkin_type_id" value="77">
                    <div class="form-group">
                        <label for="number">{{__('Number of people')}}</label>
                        <span id="number"></span>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <span id="description"></span>
                    </div>
                    <div class="form-group">
                        <label for="workers">
                            {{__('Workers')}}
                        </label>
                        <ul id="workers">
                        </ul>
                    </div>
                    <div class="form-group">
                        <label for="start">
                            {{__('Start')}}
                        </label>
                        <span id="start_show_time"></span>
                    </div>
                    <div class="form-group">
                        <label for="in_work">
                            {{__('In work')}}
                        </label>
                        <span id="in_work_show_time"></span>
                     
                    </div>
                    <div class="form-group">
                        <label for="completed">
                            {{__('Completed')}}
                        </label>
                        <span id="completed_show_time"></span>                        
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
                                <input type="hidden" name="comment" id="comment_reject">
                                <input type="hidden" name="calling_id" id="calling_idrj" >
                                <input type="hidden" name="checkin_type_id" id="checkin_type_id" value="78">
                                <button type="submit"  class="btn btn-danger w-100">{{__('Reject')}}</button>
                            </form> 
                        </div>
                        
                    </div>      

                    
            

            </div>
        </div>
    </div>
</div>
    <script>
        
        const search = document.getElementById('search');
        var Vcallings =   {!! json_encode($callings) !!};
        var alarm_position = @json($alarm_position);
        if (!Array.isArray(Vcallings)) {
             Vcallings = Object.values(Vcallings);
        }
        document.addEventListener("DOMContentLoaded", function() {
            renderTable(Vcallings);
        });
        function hideModalWin() {
            $('#modalWin').modal('hide');
        }
            // Now we can use `forEach` on Vcallings

    
            function hideModalReserve(){
                $('#modalReserve').modal('hide');
            }
      //  console.log(Vcallings); 

        
        function ShowModalWin(calling_id) {
            const calling = Object.values(Vcallings).find(calling => calling.id === calling_id);
            
            // Устанавливаем ID вызова
            document.getElementById('calling_id').value = calling_id;
            document.getElementById('calling_idrj').value = calling_id;
            
            // Устанавливаем количество работников
            document.getElementById('number').textContent = calling.workers.length;
            
            // Устанавливаем описание
            document.getElementById('description').textContent = calling.description;
            
            // Список работников
            document.getElementById('workers').innerHTML = '';
            calling.workers.forEach(worker => {
                const li = document.createElement('li');
                li.textContent = worker.fio;
                document.getElementById('workers').appendChild(li);
            });
            document.getElementById('start_show_time').textContent = new Date(calling.start_time).toLocaleString();
            document.getElementById('in_work_show_time').textContent = new Date(calling.arrival_time).toLocaleString();
            document.getElementById('completed_show_time').textContent = new Date(calling.end_time).toLocaleString();

            // Открываем модальное окно
            $('#modalWin').modal('show');
        }
        const searchInput = document.getElementById('search');
        const tableBody = document.querySelector('#callingsTable tbody');

        // Status mappings for colors and labels
        const statusMap = {
            'for_print': { color: 'bg-info', text: '{{__('For print')}}' },
            'workshop-chief': { color: 'bg-light', text: ' {{__('Workshop chief')}}' },
            'boss': { color: 'bg-light', text: ' {{__('Boss')}}' },
            'SVNtaPB': { color: 'bg-light', text: ' {{__('SVNtaPB')}}' },
            'VONtaOP': { color: 'bg-success', text: ' {{__('VONtaOP')}}' },
            'profcom': { color: 'bg-light', text: ' {{__('Profcom')}}' },
            'created': { color: 'bg-warning', text:' {{__('Created')}}' },
            'supervision': { color: 'bg-warning', text: ' {{__('Supervision')}}' },
            'default': { color: 'bg-danger', text: ' {{__('Not found')}}' },
        };
        // Initial render
        

        // Search function
        searchInput.addEventListener('keyup', (e) => {
        const searchValue = e.target.value.toLowerCase();

        const filteredCallings = Vcallings.filter(calling => {
            // Check if calling fields match the search value
            const descriptionMatch = calling.description?.toLowerCase().includes(searchValue);
            const idMatch = String(calling.id).includes(searchValue);
            const startDateMatch = calling.start_time?.toLowerCase().includes(searchValue);
            const endDateMatch = calling.end_time?.toLowerCase().includes(searchValue);

            // Check if any worker's fields match the search value
            const workerMatch = calling.workers.some(worker => {
                const fullName = worker.fio.toLowerCase(); // Full name
                const firstNameMatch = fullName.includes(searchValue);

                const divisionNameMatch = worker.divisions[0]?.name?.toLowerCase().includes(searchValue);
                const positionMatch = worker.positions[0]?.name?.toLowerCase().includes(searchValue);
                const phoneMatch = worker.phone?.toLowerCase().includes(searchValue);

                return firstNameMatch || divisionNameMatch || positionMatch || phoneMatch;
            });

            // Return true if any of the calling or worker fields match
            return descriptionMatch || idMatch || startDateMatch || endDateMatch || workerMatch;
            });
            // Render the filtered table
            renderTable(filteredCallings);
        });

        
    </script>
@endsection