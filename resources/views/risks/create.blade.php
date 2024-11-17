@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Alerts --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif
        @if(session('success')) <div class="alert alert-success">{{ __(session('success')) }}</div> @endif
        @if(session('error')) <div class="alert alert-danger">{{ __(session('error')) }}</div> @endif

        <div class="row">
            <div class="col-md-12">
                <h1>{{ __('New Experience') }}</h1>
                <form method="POST" action="{{ route('risks.store') }}">
                    @csrf
                    {{-- Language & Year --}}
                    <div class="row mb-3 p-3" style="background-color: #f0f8ff; border-radius: 8px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="lang">{{ __('Language') }}</label>
                                <select class="form-control" id="lang" name="lang" required>
                                    <option value="uk" selected >{{ __('Ukrainian') }}</option>
                                    <option value="en">{{ __('English') }}</option>
                                    <option value="ru">{{ __('Russian') }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="accepted">{{ __('Using') }}</label>
                                <select class="form-control" id="accepted" name="accepted" required>
                                    <option value="2" selected>{{ __('All') }}</option>
                                    <option value="1">{{ __('Only me') }}</option>
                                    <option value="0">{{ __('Draft') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 border">
                            <div class="form-group">
                                <label for="year">{{ __('Year') }}</label>
                                <input type="number" class="form-control" id="year" name="year" min="1900" max="{{ date('Y') }}" value="{{ date('Y') }}" style="max-width: 100px;" required>
                            </div>
                        </div>                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="location">{{ __('Location') }}</label>
                                <div class="form-check">
                                <input type="radio" id="npp_0" name="npp" value="0" > {{__('Foreign NPP')}}
                                </div>
                                <div class="form-check">
                                <input type="radio" id="npp_1" name="npp" value="1"> {{__('Ukrainian NPP')}}
                                </div>
                                <div class="form-check">
                                <input type="radio" id="npp_2" name="npp" value="2" checked> {{__('KHNPP')}}                               
                                </div>
                            </div>
                        </div>
                        {{--consequences  <a title="5-повреждения топлива итд , 4- останов --1 легкие повреждения">Тяжесть последствий</a></span>--}}
                        <div class="col-md-3 border">
                            <div class="form-group">
                                <label for="consequences">{{ __('Consequences') }}</label>
                                <div class="form-check">
                                <input type="radio" id="consequences_5" name="consequence" value="5"  required> {{__('Fuels damages')}}
                                </div>
                                <div class="form-check">
                                <input type="radio" id="consequences_4" name="consequence" value="4"> {{__('Reactor shutdown with safety systems')}}
                                </div>
                                <div class="form-check">
                                <input type="radio" id="consequences_3" name="consequence" value="3"> {{__('Reactor trip without safety systems')}}
                                </div>
                                <div class="form-check">
                                <input type="radio" id="consequences_2" name="consequence" value="2"> {{__('Reactor slowdown')}} 
                                </div>
                                <div class="form-check">
                                <input type="radio" id="consequences_1" name="consequence" value="1"> {{__('Minor damages')}}
                                </div>
                                <div class="form-check">
                                <input type="radio" id="consequences_0" name="consequence" value="0" checked> {{__('No damages')}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Text Section --}}
                    <div class="mb-3 p-3" style="background-color: #e6ffe6; border-radius: 8px;">
                        <div class="form-group">
                            <label for="text">{{ __('Text of Experience') }}</label>
                            <textarea class="form-control" id="text" name="text" rows="10" required></textarea>
                        </div>
                    </div>
                    {{-- Actions & Equipments --}}
                    <div class="row mb-3 p-3" style="background-color: #fff0f5; border-radius: 8px;">
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label>{{ __('Actions') }}</label>
                                @foreach($actions as $action)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="actions[]" value="{{ $action->id }}">
                                        <label class="form-check-label">{{ $action->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div> 
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label>{{ __('Equipments') }}
                                    <button class="btn" onclick="selectAll('equipments')" >{{__('All')}}</button>
                                </label>
                                @foreach($equipments as $equipment)
                                    <div class="form-check">

                                        <input class="form-check-input" type="checkbox" name="equipments[]" value="{{ $equipment->id }}">
                                        <label class="form-check-label">{{ $equipment->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    {{-- Causes & Systems --}}
                    <div class="row mb-3 p-3" style="background-color: #f5f5dc; border-radius: 8px;">
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label>{{ __('Causes') }}
                                    <button class="btn" onclick="selectAll('causes')" >{{__('All')}}</button>
                                </label>
                                @foreach($causes as $cause)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="causes[]" value="{{ $cause->id }}">
                                        <label class="form-check-label">{{ $cause->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label for="systems">{{ __('Systems') }}
                                    <button class="btn" onclick="selectsAll('systems')" >{{__('All')}}</button>
                                </label>
                                <select class="form-control" id="systems" name="systems[]" size="8" multiple>
                                   
                                    @foreach($systems as $subsystem)
                                        <option value="{{ $subsystem->id }}">{{ $subsystem->abv }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-primary w-100">{{ __('Create') }}</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function selectAll(name) {
            var checkboxes = document.getElementsByName(name + '[]');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = true;
            }
        }
        function selectsAll(name) {
            var checkboxes = document.getElementById(name);
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].selected = true;
            }
        }
    </script>
@endsection
