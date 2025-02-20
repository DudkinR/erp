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
            <div class="col-md-6">
                <h1 class="d-inline-block mr-2">{{ __('Form of callings') }}</h1>
            </div>
            <div class="col-md-6">
  
                <form action="/Icallings" method="post" class="form-inline" onsubmit="return validateFilters()">
                    @csrf
                    <select name="filter" class="form-control" onchange="sendThisForm(this)">
                        <option value="all">{{ __('All') }}</option>
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
        <div class="row">
            <div class="col-md-6">
                <a class="btn btn-warning w-100" href="{{ route('callings.create') }}">{{__('New')}}</a>
            </div>
            <div class="col-md-6">
                <a class="btn btn-info w-100" onclick="$('#modalReserve').modal('show')">{{__('Reserve')}}</a>
            </div>
        </div>  
        <div class="container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        @php
                        @endphp
                        <th>{{__('№ of form')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Start')}}</th>
                        <th>{{__('Completed')}}</th>
                        <th>{{__('Department')}}</th>
                        <th>{{__('Position')}}</th>
                        <th>{{__('PIB')}}</th>
                        <th>{{__('SS')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($callings as $calling)
                    <tr>
                        <td>{{$calling->id}}</td>
                        <td>{{$calling->description}}
                            <a href="{{ route('callings.edit', $calling->id) }}" class ="btn w-100 btn-warning">
                                {{__("Edit")}}                                
                                </a>
                                <a href="{{ route('callings.show', $calling->id) }}" class ="btn w-100 btn-info">
                                {{__("Show")}}                                
                                </a>
                        </td>
                        <td title="{{ $calling->start_time }}"
                            @if($calling->start_time==null && $calling->personal_start_id==null)
                                style="background-color: #ff8040"
                                @elseif($calling->start_time!==null && $calling->personal_start_id==null)
                                style="background-color: #ffff80"                                
                            @endif
                            >  
                            @if($calling->start_time!==null&& $calling->personal_start_id!==null)                          
                            {{ \Carbon\Carbon::parse($calling->start_time)->format('H:i') }}
                            @elseif($calling->start_time!==null&& $calling->personal_start_id==null)
                            <a  class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->start_time)->format('H:i') }}</a>
                            @else
                            -----
                            @endif
                        </td>
                        <td title="{{ $calling->end_time }}"
                            @if($calling->end_time==null && $calling->personal_end_id==null)
                                style="background-color: #ff8040 "
                                @elseif($calling->end_time!==null && $calling->personal_end_id==null)
                                style="background-color: #ffff80"
                            @endif
                            >
                            @if($calling->end_time!==null && $calling->personal_end_id!==null)

                            {{ \Carbon\Carbon::parse($calling->end_time)->format('H:i') }}
                            @elseif($calling->end_time!==null && $calling->personal_end_id==null)
                            <a   class="btn btn-warning" title="{{__('Confirm')}}"> {{ \Carbon\Carbon::parse($calling->end_time)->format('H:i') }}</a>
                            @else
                            ------
                            @endif
                        </td>
                        <td>
                           @php  
                           $mass_divisions=[]; 
                           $Kerivnyk_bryhady=App\Models\Type::where('name','Керівник бригади')->first();
                           @endphp
                            @foreach($calling->workers as $worker)
                                      {{ $worker->divisions[0]->name }} 
                                    <hr>                                 
                            @endforeach

                            
                        </td>
                        <td>
                            @foreach($calling->workers as $worker)
                      
                      
                               <b> {{$worker->positions[0]->name}}</b>
                                <hr>
                        @endforeach
                            
                        </td>                        
                        <td >
                            @foreach($calling->workers as $worker)
                                {{$worker->fio}}
                                <hr>
                            @endforeach
                        </td>
                       

                        
                        <td> 
                            {{$calling->start_personal_id}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        
        </div>
    </div>
</div>
<div class="modal" id="modalReserve">
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
                    <input type="hidden" name="filter" value="{{$filter ?? ''}}">
                    <div class="form-group">
                        <label for="tab_number">{{__('Tab Number of people')}}</label>
                        <input type="hidden" name="tab_number" id="tab_number" class="form-control" value="{{Auth::user()->tn}}">
                        <div id=show_personel>
                            {{Auth::user()->personal->fio}}
                        </div>
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

    <script>
            function hideModalReserve() {
            $('#modalReserve').modal('hide');
        }
    </script>
@endsection