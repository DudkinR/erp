@extends('layouts.app')
@section('content')
@php   $types= ['text'=>0, 'string'=>1, 'number'=>2, 'float'=>3, 'time'=>4, 'boolean'=>5]; @endphp
    <div class="container">
        <!-- Display Validation Errors -->
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
                <h2>{{__('Create')}}</h2>
                <form method="POST" action="{{ route('mag.update', $magtable) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" 
                        value= "{{$magtable->name}}" >
                        
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="3" 
                        >{{$magtable->description }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="division_writer">{{__('Division Writer')}}</label>                        
                        <select class="form-control" id="division_writer" name="division_writer[]" multiple >
                            <option value="all">{{ __('All') }}</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}"
                                @if($magtable->divisions->contains(function($d) use ($division) {
                                    return $d->id == $division->id && $d->pivot->type == 0;
                                })) selected @endif>{{ $division->name }}</option>
                            @endforeach
                        </select>                        
                    </div>
                    <div class="form-group">
                        <label for="division_reader">{{__('Division Reader')}}</label>
                        <select class="form-control" id="division_reader" name="division_reader[]" multiple >
                            <option value="all">{{__('All')}}</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}"
                                @if($magtable->divisions->contains(function($d) use ($division) {
                                    return $d->id == $division->id && $d->pivot->type == 1;
                                })) selected @endif>{{ $division->name }}</option>                               
                            @endforeach
                        </select>
                    </div>
                    <div class="container" id ="columns">
                        @foreach($magtable->magcolumns as $column)
                            <div class="row bg-blue-200">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="column_name">{{ $loop->index+1 }} {{__('Column Name')}} </label>
                                        <input type="text" class="form-control" id="column_name_{{ $loop->index }}" name="column_name_{{$column->id}}" value="{{ $column->name }}" >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="dimension">{{__('Dimension')}}</label>
                                        <select class="form-control" id="dimension_{{ $loop->index }}" name="dimension__{{$column->id}}">
                                            <option value="" selected></option>
                                            @foreach($dimensions as $dimension)
                                                <option value="{{ $dimension['value'] }}"
                                                @if($column->dimension == $dimension['value']) selected @endif>{{ $dimension['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" >
                                        <label for="column_type">{{__('Column Type')}}</label>
                                        
                                        <select class="form-control" id="column_type_{{ $loop->index }}" name="column_type_{{$column->id}}" onchange="showLimits(this.value, {{ $loop->index }})">
                                            <option value="text" @if($column->type == $types['text']) selected @endif>{{__('Text')}}</option>
                                            <option value="string" @if($column->type == $types['string']) selected @endif>{{__('String')}}</option>
                                            <option value="number" @if($column->type == $types['number']) selected @endif>{{__('Number')}}</option>
                                            <option value="float" @if($column->type == $types['float']) selected @endif>{{__('Float')}}</option>
                                            <option value="time" @if($column->type == $types['time']) selected @endif>{{__('Time')}}</option>
                                            <option value="boolean" @if($column->type == $types['boolean']) selected @endif>{{__('Boolean')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="description">{{__('Description of column')}}</label>
                                        <textarea class="form-control" id="description_{{ $loop->index }}" name="description_{{$column->id}}" rows="3" >{{ $column->description }}</textarea>
                                    </div>
                                </div>
                            </div>                            
                            <div class="container" id="limits_{{ $loop->index }}" style="display:flex; flex-wrap:wrap; 
                            @if($column->type != $types['float'] && $column->type != $types['number'])
                                display:none;
                            @endif">
                            @php 
                                $limits = $column->maglimits; 
                                if($limits->count() !== 0) {
                                    $high_fix_limit = $limits[0]->hfb;
                                    $high_emergency_limit = $limits[0]->heb;
                                    $high_reglement_limit = $limits[0]->hrb;
                                    $high_working_limit = $limits[0]->hwb;
                                    $low_fix_limit = $limits[0]->lfb;
                                    $low_emergency_limit= $limits[0]->leb;
                                    $low_reglement_limit = $limits[0]->lrb;
                                    $low_working_limit =$limits[0]->lfb;
                                }
                                else {
                                    $high_fix_limit =0;
                                    $high_emergency_limit = 0;
                                    $high_reglement_limit = 0;
                                    $high_working_limit = 0;
                                    $low_fix_limit = 0;
                                    $low_emergency_limit= 0;
                                    $low_reglament_limit =0;
                                    $low_working_limit =0;   
                                }
                            @endphp                           
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>{{ __('Upper Limits') }}</h5>
                                    </div>
                                    <div class="col-md-2 bg-gray-100">
                                        <div class="form-group"
                                        >
                                            <label for="high_fix_limit">{{ __('High Fix Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_fix_limit_{{$column->id}}" name="high_fix_limit_{{$column->id}}" value="{{$high_fix_limit}}"> 
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-red-500">
                                        <div class="form-group">
                                            <label for="high_emergency_limit">{{ __('High Emergency Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_emergency_limit_{{$column->id}}" name="high_emergency_limit_{{$column->id}}" value="{{$high_emergency_limit}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-yellow-300">
                                        <div class="form-group">
                                            <label for="high_reglement_limit">{{ __('High Reglement Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_reglement_limit_{{$column->id}}" name="high_reglement_limit_{{$column->id}}" value="{{ $high_reglement_limit }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-green-400">
                                        <div class="form-group">
                                            <label for="high_working_limit">{{ __('High Working Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_working_limit_{{$column->id}}" name="high_working_limit_{{$column->id}}" value="{{ $high_working_limit }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>{{ __('Lower Limits') }}</h5>
                                    </div>
                                    <div class="col-md-2 bg-gray-100">
                                        <div class="form-group">
                                            <label for="low_fix_limit">{{ __('Low Fix Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_fix_limit_{{$column->id}}" name="low_fix_limit_{{$column->id}}" value="{{ $low_fix_limit }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-red-500">
                                        <div class="form-group">
                                            <label for="low_emergency_limit">{{ __('Low Emergency Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_emergency_limit_{{$column->id}}" name="low_emergency_limit_{{$column->id}}" value="{{ $low_emergency_limit }}">

                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-yellow-300">
                                        <div class="form-group">
                                            <label for="low_reglement_limit">{{ __('Low Reglement Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_reglement_limit_{{$column->id}}" name="low_reglement_limit_{{$column->id}}" value="{{ $low_reglement_limit }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-green-400">
                                        <div class="form-group">
                                            <label for="low_working_limit">{{ __('Low Working Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_working_limit_{{$column->id}}" name="low_working_limit_{{$column->id}}" value="{{ $low_working_limit }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="row bg-blue-200">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="new_column_name">{{__('New Column Name')}}</label>
                                    <input type="text" class="form-control" id="new_column_name_0" name="new_column_name[]" value="" >
                                </div>
                            </div>
                            <!-- dimensions resources\views\components\select_dimensions.blade.php -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="new_dimension">{{__('Dimension')}}</label>
                                       @include('components.dimensions')
                                        <select class="form-control" id="new_dimension_0" name="new_dimension_0">
                                            <option value="" selected></option>
                                            @foreach($dimensions as $dimension)
                                                <option value="{{ $dimension['value'] }}"
                                                >{{ $dimension['label'] }}</option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>                         
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="column_type">{{__('Column Type')}}</label>
                                    <select class="form-control" id="new_column_type_0" name="new_column_type_0"
                                            onchange="showLimits(this.value, 0)">                                    >
                                        <option value="text" selected >{{__('Text')}}</option>
                                        <option value="string">{{__('String')}}</option>
                                        <option value="number">{{__('Number')}}</option>
                                        <option value="float">{{__('Float')}}</option>
                                        <option value="time">{{__('Time')}}</option>
                                        <option value="boolean">{{__('Boolean')}}</option>
                                    </select>
                                </div>
                            </div>                            
                        </div>   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">{{__('Description of column')}}</label>
                                    <textarea class="form-control" id="new_description_0" name="new_description_0" rows="3"  >{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <!-- hidden row with hand over data  limits-->
                        <div class="container" id="limits_0" style="display:flex; flex-wrap:wrap; display:none;">
                            <!-- Container for Upper Limits -->                            
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>{{ __('Upper Limits') }}</h5>
                                    </div>
                                        <div class="col-md-2 bg-gray-100"> 
                                        <div class="form-group">
                                            <label for="high_fix_limit">{{ __('High Fix Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_fix_limit_0" name="new_high_fix_limit_0" value="{{ old('high_fix_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-red-500 ">
                                        <div class="form-group">
                                            <label for="high_emergency_limit">{{ __('High Emergency Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_emergency_limit_0" name="new_high_emergency_limit_0" value="{{ old('high_emergency_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-yellow-300">
                                        <div class="form-group">
                                            <label for="high_reglement_limit">{{ __('High Reglement Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_reglement_limit_0" name="new_high_reglement_limit_0" value="{{ old('high_reglement_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-green-400">
                                        <div class="form-group">
                                            <label for="high_working_limit">{{ __('High Working Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_working_limit_0" name="new_high_working_limit_0" value="{{ old('high_working_limit') }}">
                                        </div>
                                    </div>
                                </div>
                            <!-- Container for Lower Limits -->
                            <div class="row">
                                    <div class="col-md-3">
                                          <h5>{{ __('Lower Limits') }}</h5>
                                    </div><div class="col-md-2 bg-gray-100"> 
                                        <div class="form-group">
                                            <label for="low_fix_limit">{{ __('Low Fix Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_fix_limit_0" name="new_low_fix_limit_0" value="{{ old('low_fix_limit') }}">
                                        </div>
                                    </div><div class="col-md-2 bg-red-500">
                                        <div class="form-group">
                                            <label for="low_emergency_limit">{{ __('Low Emergency Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_emergency_limit_0" name="new_low_emergency_limit_0" value="{{ old('low_emergency_limit') }}">
                                        </div>
                                    </div><div class="col-md-2 bg-yellow-300">
                                        <div class="form-group">
                                            <label for="low_reglement_limit">{{ __('Low Reglement Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_reglement_limit_0" name="new_low_reglement_limit_0" value="{{ old('low_reglement_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-green-400">
                                        <div class="form-group">
                                            <label for="low_working_limit">{{ __('Low Working Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_working_limit_0" name="new_low_working_limit_0" value="{{ old('low_working_limit') }}">
                                        </div>
                                    </div>
                            </div>                            
                        </div>
                        <hr style="border: 1px solid blue;">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary w-100" onclick="addColumn()">{{__('Add Column')}}</button>
                        </div>
                    </div>
                    <hr>
                    <div class="container" id ="memories">
                        <!-- selected from exist -->
                        @php 
                            $memories = $magtable->magmems; 
                        @endphp
                        @if($mems->count() > 0)
                        <div class="row bg-blue-100">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="memory">{{__('Memory')}}</label>
                                    <select class="form-control" id="memory" name="memory[]" multiple >                                        
                                        @foreach($mems as $memory)
                                            <option value="{{ $memory->id }}"
                                            @if($memories->contains($memory)) selected @endif>
                                            {{ $memory->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <input type="hidden" name="memory_id" id="memory_id" value="0">
                        <!-- name	description -->
                        <div class="row bg-blue-200">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="memory_name">{{__('Memory Name')}}</label>
                                    <input type="text" class="form-control" id="memory_name_0" name="memory_name[]" value="" >
                                </div>
                            </div>  
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="memory_description">{{__('Memory Description')}}</label>
                                    <textarea class="form-control" id="memory_description_0" name="memory_description[]" rows="3" >{{ old('memory_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <hr style="border: 1px solid blue;">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary w-100" onclick="addMemory()">{{__('Add Memory')}}</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
    <div id="translations" 
     data-column-name="{{ __('Column Name') }}"
     data-column-type="{{ __('Column Type') }}"
     data-upper-limits="{{ __('Upper Limits') }}"
     data-lower-limits="{{ __('Lower Limits') }}"
     data-high-fix-limit="{{ __('High Fix Limit') }}"
     data-high-emergency-limit="{{ __('High Emergency Limit') }}"
     data-high-reglement-limit="{{ __('High Reglement Limit') }}"
     data-high-working-limit="{{ __('High Working Limit') }}"
     data-low-working-limit="{{ __('Low Working Limit') }}"
     data-low-reglement-limit="{{ __('Low Reglement Limit') }}"
     data-low-emergency-limit="{{ __('Low Emergency Limit') }}"
     data-low-fix-limit="{{ __('Low Fix Limit') }}"
     data-text="{{ __('Text') }}"
    data-string="{{ __('String') }}"
    data-number="{{ __('Number') }}"
    data-float="{{ __('Float') }}"
    data-time="{{ __('Time') }}"
    data-boolean="{{ __('Boolean') }}"
    data-dimension="{{ __('Dimension') }}"
    data-description="{{ __('Description') }}"
    data-memory-name="{{ __('Memory Name') }}"
    data-memory-description="{{ __('Memory Description') }}"
    >

</div>
    <script>
        const limits = @json(\App\Models\Maglimit::all()); 
        let column_id = 0;
        let memory_id = 0;

/// Select type of column float or number to show limits
function showLimits(column_type, column_id) {
    // Find the limits container by ID
    const limitsContainer = document.getElementById('limits_' + column_id);

    // Check if the limits container exists
    if (!limitsContainer) {
        console.error(`Limits container with ID 'limits_${column_id}' not found.`);
        return;
    }

    // Show or hide the limits based on the column type
    if (column_type === 'float' || column_type === 'number') {
        limitsContainer.style.display = 'block';
    } else {
        limitsContainer.style.display = 'none';
    }
}

        /// add new column
function addColumn() {
    column_id++;
    // Get translations from the data attributes
    const translations = document.getElementById('translations').dataset;

    let column = `
        <div class="row bg-blue-200">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="new_column_name">${translations.columnName}</label>
                    <input type="text" class="form-control" id="new_column_name_${column_id}" name="new_column_name[]" value="" >  
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="new_dimension">${translations.dimension}</label>
                    <select class="form-control" id="new_dimension_${column_id}" name="new_dimension_${column_id}">
                        <option value="" selected></option>
                        @foreach($dimensions as $dimension)
                            <option value="{{ $dimension['value'] }}">{{ $dimension['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="new_column_type">${translations.columnType}</label>
                    <select class="form-control" id="new_column_type_${column_id}" name="new_column_type_${column_id}" onchange="showLimits(this.value, ${column_id})">
                        <option value="text">${translations.text}</option>
                        <option value="string">${translations.string}</option>
                        <option value="number">${translations.number}</option>
                        <option value="float">${translations.float}</option>
                        <option value="time">${translations.time}</option>
                        <option value="boolean">${translations.boolean}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="new_description">${translations.description}</label>
                    <textarea class="form-control" id="new_description_${column_id}" name="new_description_${column_id}" rows="3" >{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="container" id="new_limits_${column_id}" style="display:flex; flex-wrap:wrap; display:none;">
            <div class="row">
                <div class="col-md-3">
                    <h5>${translations.upperLimits}</h5>
                </div>
                <!-- Upper Limits Inputs -->
                <div class="col-md-2 bg-gray-100">
                    <div class="form-group">
                        <label for="high_fix_limit">${translations.highFixLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_fix_limit_${column_id}" name="new_high_fix_limit_${column_id}" value="{{ old('high_fix_limit') }}">
                    </div>
                </div>
                <div class="col-md-2 bg-red-500">
                    <div class="form-group">
                        <label for="high_emergency_limit">${translations.highEmergencyLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_emergency_limit_${column_id}" name="new_high_emergency_limit_${column_id}" value="{{ old('high_emergency_limit') }}">
                        </div>
                        </div>
                        <div class="col-md-2 bg-yellow-300">
                        <div class="form-group">
                        <label for="high_reglement_limit">${translations.highReglementLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_reglement_limit_${column_id}" name="new_high_reglement_limit_${column_id}" value="{{ old('high_reglement_limit') }}">
                        </div>
                        </div>
                        <div class="col-md-2 bg-green-400">
                        <div class="form-group">
                        <label for="high_working_limit">${translations.highWorkingLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_high_working_limit_${column_id}" name="new_high_working_limit_${column_id}" value="{{ old('high_working_limit') }}">
                        </div>
                        </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <h5>${translations.lowerLimits}</h5>
                </div>
                <!-- Lower Limits Inputs --><div class="col-md-2 bg-gray-100">
                        <div class="form-group">
                        <label for="low_fix_limit">${translations.lowFixLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_fix_limit_${column_id}" name="new_low_fix_limit_${column_id}" value="{{ old('low_fix_limit') }}">
                        </div>
                        </div>  <div class="col-md-2 bg-red-500">
                        <div class="form-group">
                        <label for="low_emergency_limit">${translations.lowEmergencyLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_emergency_limit_${column_id}" name="new_low_emergency_limit_${column_id}" value="{{ old('low_emergency_limit') }}">
                        </div>
                        </div><div class="col-md-2 bg-yellow-300">
                    <div class="form-group">
                        <label for="low_reglement_limit">${translations.lowReglementLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_reglement_limit_${column_id}" name="new_low_reglement_limit_${column_id}" value="{{ old('low_reglement_limit') }}">
                        </div>
                        </div>
                <div class="col-md-2 bg-green-400">
                    <div class="form-group">
                        <label for="low_working_limit">${translations.lowWorkingLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="new_low_working_limit_${column_id}" name="new_low_working_limit_${column_id}" value="{{ old('low_working_limit') }}">
                    </div>
                </div>
                <!-- More Lower Limit Fields -->
            </div>
        </div>
        <hr style="border: px solid blue;">
    `;
    document.getElementById('columns').insertAdjacentHTML('beforeend', column);
}
 function addMemory() {
    memory_id++;
    // Get translations from the data attributes
    const translations = document.getElementById('translations').dataset;
    let memory = `
        <div class="row bg-blue-200">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="memory_name">${translations.memoryName}</label>
                    <input type="text" class="form-control" id="memory_name_${memory_id}" name="memory_name[]" value="" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="memory_description">${translations.memoryDescription}</label>
                    <textarea class="form-control" id="memory_description_${memory_id}" name="memory_description[]" rows="3" >{{ old('memory_description') }}</textarea>
                </div>
            </div>
        </div>
        <hr style="border: 1px solid blue;">
    `;
    document.getElementById('memories').insertAdjacentHTML('beforeend', memory);
    document.getElementById('memory_id').value = memory_id;

}
    </script>
@endsection