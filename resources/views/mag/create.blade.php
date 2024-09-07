@extends('layouts.app')
@section('content')
<?php 
$dimensions = [
    ['value' => 'kg/cm2',   'label' => __('kg/cm2')],
    ['value' => 'kg/m2',    'label' => __('kg/m2')],    
    ['value' => 'mm', 'label' => __('mm')],
    ['value' => 'cm', 'label' => __('cm')],
    ['value' => 'm', 'label' => __('m')],
    ['value' => 'km', 'label' => __('km')],
    ['value' => 'g', 'label' => __('g')],
    ['value' => 'kg', 'label' => __('kg')],
    ['value' => 'l', 'label' => __('l')],
    ['value' => 'm3', 'label' => __('m3')],
    ['value' => 'm3/h', 'label' => __('m3/h')],
    ['value' => 'm3/s', 'label' => __('m3/s')],
    ['value' => 'cm2', 'label' => __('cm2')],
    ['value' => 'm2', 'label' => __('m2')],
    ['value' => 'm/s', 'label' => __('m/s')],
    ['value' => 'km/h', 'label' => __('km/h')],
    // химия
    ['value' => 'mol', 'label' => __('mol')],
    ['value' => 'mol/l', 'label' => __('mol/l')],
    ['value' => 'mol/m3', 'label' => __('mol/m3')],
    ['value' => 'mol/m2', 'label' => __('mol/m2')],
    ['value' => 'mol/s', 'label' => __('mol/s')],
    ['value' => 'mol/m2/s', 'label' => __('mol/m2/s')],
    ['value' => 'mol/m3/s', 'label' => __('mol/m3/s')],
    ['value' => 'mol/l/s', 'label' => __('mol/l/s')],
    ['value' => 'mol/l/m2', 'label' => __('mol/l/m2')],
    ['value' => 'mol/l/m3', 'label' => __('mol/l/m3')],
    ['value' => 'mol/m2/s', 'label' => __('mol/m2/s')],
    ['value' => 'mol/m3/s', 'label' => __('mol/m3/s')],
    ['value' => 'mol/l/s', 'label' => __('mol/l/s')],
    ['value' => 'mol/l/m2', 'label' => __('mol/l/m2')],
    ['value' => 'mol/l/m3', 'label' => __('mol/l/m3')],
    ['value' => 'mol/m2/s', 'label' => __('mol/m2/s')],
    ['value' => 'mol/m3/s', 'label' => __('mol/m3/s')],
    ['value' => 'mol/l/s', 'label' => __('mol/l/s')],
    ['value' => 'mol/l/m2', 'label' => __('mol/l/m2')],
    ['value' => 'mol/l/m3', 'label' => __('mol/l/m3')],
    ['value' => 'mol/m2/s', 'label' => __('mol/m2/s')],
    ['value' => 'mol/m3/s', 'label' => __('mol/m3/s')],
    ['value' => 'mol/l/s', 'label' => __('mol/l/s')],
    ['value' => 'mol/l/m2', 'label' => __('mol/l/m2')],
    ['value' => 'mol/l/m3', 'label' => __('mol/l/m3')],
    // phisics
    ['value' => 'A', 'label' => __('A')],
    ['value' => 'V', 'label' => __('V')],
    ['value' => 'W', 'label' => __('W')],
    ['value' => 'J', 'label' => __('J')],
    ['value' => 'N', 'label' => __('N')],
    ['value' => 'Pa', 'label' => __('Pa')],
    ['value' => 'C', 'label' => __('C')],
    ['value' => 'F', 'label' => __('F')],
    ['value' => 'S', 'label' => __('S')],
    ['value' => 'H', 'label' => __('H')],
    ['value' => 'T', 'label' => __('T')],
    ['value' => 'Wb', 'label' => __('Wb')],
    ['value' => 'H', 'label' => __('H')],
    ['value' => 'lm', 'label' => __('lm')],
    ['value' => 'lx', 'label' => __('lx')],
    ['value' => 'Bq', 'label' => __('Bq')],
    ['value' => 'Gy', 'label' => __('Gy')],
    ['value' => 'Sv', 'label' => __('Sv')],
    ['value' => 'kat', 'label' => __('kat')],
    ['value' => 'rad', 'label' => __('rad')],
    ['value' => 'rem', 'label' => __('rem')],
    ['value' => 'Ci', 'label' => __('Ci')],
    ['value' => 'R', 'label' => __('R')],
    ['value' => 'rd', 'label' => __('rd')],
    ['value' => 'eV', 'label' => __('eV')],
    ['value' => 'MeV', 'label' => __('MeV')],
    ['value' => 'GeV', 'label' => __('GeV')],
    ['value' => 'TeV', 'label' => __('TeV')],
    ['value' => 'K', 'label' => __('K')],
    ['value' => '°C', 'label' => __('°C')],
    ['value' => '°F', 'label' => __('°F')],
    ['value' => '°K', 'label' => __('°K')],
];
 ?>
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
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Create')}}</h1>
                <form method="POST" action="{{ route('mag.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required
                        >{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="division_writer">{{__('Division Writer')}}</label>
                        @php $divisions = App\Models\Division::all(); @endphp
                        <select class="form-control" id="division_writer" name="division_writer[]" multiple required>
                            <option value="all">{{__('All')}}</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>     
                    </div>
                    <div class="form-group">
                        <label for="division_reader">{{__('Division Reader')}}</label>
                        <select class="form-control" id="division_reader" name="division_reader[]" multiple required>
                            <option value="all">{{__('All')}}</option>
                            @foreach($divisions as $division)
                                <option value="{{ $division->id }}">{{ $division->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="container" id ="columns">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="column_name">{{__('Column Name')}}</label>
                                    <input type="text" class="form-control" id="column_name_0" name="column_name[]" value="" required>
                                </div>
                            </div>
                            <!-- dimensions resources\views\components\select_dimensions.blade.php -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="dimension">{{__('Dimension')}}</label>
                                       @include('components.dimensions')
                                        <select class="form-control" id="dimension_0" name="dimension_0">
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
                                    <select class="form-control" id="column_type_0" name="column_type_0"
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
                              
                            
                        </div>    <!-- hidden row with hand over data  limits-->
                        <div class="container" id="limits_0" style="display:flex; flex-wrap:wrap; display:none;">
                            <!-- Container for Upper Limits -->
                            
                                <div class="row">
                                    <div class="col-md-3">
                                        <h5>{{ __('Upper Limits') }}</h5>
                                    </div>
                                        <div class="col-md-2 bg-gray-100"> 
                                        <div class="form-group">
                                            <label for="high_fix_limit">{{ __('High Fix Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_fix_limit_0" name="high_fix_limit_0" value="{{ old('high_fix_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-red-500 ">
                                        <div class="form-group">
                                            <label for="high_emergency_limit">{{ __('High Emergency Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_emergency_limit_0" name="high_emergency_limit_0" value="{{ old('high_emergency_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-yellow-300">
                                        <div class="form-group">
                                            <label for="high_reglement_limit">{{ __('High Reglement Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_reglement_limit_0" name="high_reglement_limit_0" value="{{ old('high_reglement_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-green-400">
                                        <div class="form-group">
                                            <label for="high_working_limit">{{ __('High Working Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_working_limit_0" name="high_working_limit_0" value="{{ old('high_working_limit') }}">
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
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_fix_limit_0" name="low_fix_limit_0" value="{{ old('low_fix_limit') }}">
                                        </div>
                                    </div><div class="col-md-2 bg-red-500">
                                        <div class="form-group">
                                            <label for="low_emergency_limit">{{ __('Low Emergency Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_emergency_limit_0" name="low_emergency_limit_0" value="{{ old('low_emergency_limit') }}">
                                        </div>
                                    </div><div class="col-md-2 bg-yellow-300">
                                        <div class="form-group">
                                            <label for="low_reglement_limit">{{ __('Low Reglement Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_reglement_limit_0" name="low_reglement_limit_0" value="{{ old('low_reglement_limit') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 bg-green-400">
                                        <div class="form-group">
                                            <label for="low_working_limit">{{ __('Low Working Limit') }}</label>
                                            <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_working_limit_0" name="low_working_limit_0" value="{{ old('low_working_limit') }}">
                                        </div>
                                    </div>
                                    
                                    
                                    
                                </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-primary w-100" onclick="addColumn()">{{__('Add Column')}}</button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">{{__('Create')}}</button>
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

     >

</div>
    <script>
        const limits = @json(\App\Models\maglimit::all()); 
        let column_id = 0;

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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="column_name">${translations.columnName}</label>
                    <input type="text" class="form-control" id="column_name_${column_id}" name="column_name[]" value="" required>  
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="dimension">${translations.dimension}</label>
                    <select class="form-control" id="dimension_${column_id}" name="dimension_${column_id}">
                        <option value="" selected></option>
                        @foreach($dimensions as $dimension)
                            <option value="{{ $dimension['value'] }}">{{ $dimension['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="column_type">${translations.columnType}</label>
                    <select class="form-control" id="column_type_${column_id}" name="column_type_${column_id}" onchange="showLimits(this.value, ${column_id})">
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
        <div class="container" id="limits_${column_id}" style="display:flex; flex-wrap:wrap; display:none;">
            <div class="row">
                <div class="col-md-3">
                    <h5>${translations.upperLimits}</h5>
                </div>
                <!-- Upper Limits Inputs -->
                <div class="col-md-2 bg-gray-100">
                    <div class="form-group">
                        <label for="high_fix_limit">${translations.highFixLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_fix_limit_${column_id}" name="high_fix_limit_${column_id}" value="{{ old('high_fix_limit') }}">
                    </div>
                </div>
                <div class="col-md-2 bg-red-500">
                    <div class="form-group">
                        <label for="high_emergency_limit">${translations.highEmergencyLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_emergency_limit_${column_id}" name="high_emergency_limit_${column_id}" value="{{ old('high_emergency_limit') }}">
                        </div>
                        </div>
                        <div class="col-md-2 bg-yellow-300">
                        <div class="form-group">
                        <label for="high_reglement_limit">${translations.highReglementLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_reglement_limit_${column_id}" name="high_reglement_limit_${column_id}" value="{{ old('high_reglement_limit') }}">
                        </div>
                        </div>
                        <div class="col-md-2 bg-green-400">
                        <div class="form-group">
                        <label for="high_working_limit">${translations.highWorkingLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="high_working_limit_${column_id}" name="high_working_limit_${column_id}" value="{{ old('high_working_limit') }}">
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
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_fix_limit_${column_id}" name="low_fix_limit_${column_id}" value="{{ old('low_fix_limit') }}">
                        </div>
                        </div>  <div class="col-md-2 bg-red-500">
                        <div class="form-group">
                        <label for="low_emergency_limit">${translations.lowEmergencyLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_emergency_limit_${column_id}" name="low_emergency_limit_${column_id}" value="{{ old('low_emergency_limit') }}">
                        </div>
                        </div><div class="col-md-2 bg-yellow-300">
                    <div class="form-group">
                        <label for="low_reglement_limit">${translations.lowReglementLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_reglement_limit_${column_id}" name="low_reglement_limit_${column_id}" value="{{ old('low_reglement_limit') }}">
                        </div>
                        </div>
                <div class="col-md-2 bg-green-400">
                    <div class="form-group">
                        <label for="low_working_limit">${translations.lowWorkingLimit}</label>
                        <input type="number" step="0.01" placeholder="NULL" class="form-control" id="low_working_limit_${column_id}" name="low_working_limit_${column_id}" value="{{ old('low_working_limit') }}">
                    </div>
                </div>
                <!-- More Lower Limit Fields -->
                
                      
                        



            </div>
        </div>
    `;
    document.getElementById('columns').insertAdjacentHTML('beforeend', column);

}
    </script>
@endsection