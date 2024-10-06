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
                <h1>{{ __('Edit Experience') }}</h1>
                <form method="POST" action="{{ route('risks.update', $experience->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Language & Year --}}
                    <div class="row mb-3 p-3" style="background-color: #f0f8ff; border-radius: 8px;">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="accepted">{{ __('Using') }}</label>
                                <select class="form-control" id="accepted" name="accepted" required>
                                    <option value="2" {{ $experience->accepted == 2 ? 'selected' : '' }}>{{ __('All') }}</option>
                                    <option value="1" {{ $experience->accepted == 1 ? 'selected' : '' }}>{{ __('Only me') }}</option>
                                    <option value="0" {{ $experience->accepted == 0 ? 'selected' : '' }}>{{ __('Draft') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 border">
                            <div class="form-group">
                                <label for="year">{{ __('Year') }}</label>
                                <input type="number" class="form-control" id="year" name="year" min="1900" max="{{ date('Y') }}" value="{{ $experience->year }}" style="max-width: 100px;" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="location">{{ __('Location') }}</label>
                                <div class="form-check">
                                    <input type="radio" id="npp_0" name="npp" value="0" {{ $experience->npp == 0 ? 'checked' : '' }}> {{ __('Foreign NPP') }}
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="npp_1" name="npp" value="1" {{ $experience->npp == 1 ? 'checked' : '' }}> {{ __('Ukrainian NPP') }}
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="npp_2" name="npp" value="2" {{ $experience->npp == 2 ? 'checked' : '' }}> {{ __('KHNPP') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 border">
                            <div class="form-group">
                                <label for="consequences">{{ __('Consequences') }}</label>
                                <div class="form-check">
                                    <input type="radio" id="consequences_5" name="consequence" value="5" {{ $experience->consequence == 5 ? 'checked' : '' }}>{{ __('Fuels damages') }}
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="consequences_4" name="consequence" value="4" {{ $experience->consequence == 4 ? 'checked' : '' }}>{{ __('Reactor shutdown with safety systems') }}
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="consequences_3" name="consequence" value="3" {{ $experience->consequence == 3 ? 'checked' : '' }}>{{ __('Reactor trip without safety systems') }}
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="consequences_2" name="consequence" value="2" {{ $experience->consequence == 2 ? 'checked' : '' }}>{{ __('Reactor slowdown') }}
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="consequences_1" name="consequence" value="1" {{ $experience->consequence == 1 ? 'checked' : '' }}>{{ __('Minor damages') }}
                                </div>
                                <div class="form-check">
                                    <input type="radio" id="consequences_0" name="consequence" value="0" {{ $experience->consequence == 0 ? 'checked' : '' }}>{{ __('No damages') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Text Section --}}
                    <div class="mb-3 p-3" style="background-color: #e6ffe6; border-radius: 8px;">
                        <div class="form-group">
                            <label for="text_uk">{{ __('Text of Experience') }} {{__('uk')}}</label>
                            <textarea class="form-control" id="text_uk" name="text_uk" rows="10" >{{ old('text', $experience->text_uk ) }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3 p-3" style="background-color: #e6ffe6; border-radius: 8px;">
                        <div class="form-group">
                            <label for="text_en">{{ __('Text of Experience') }} {{__('en')}}</label>
                            <textarea class="form-control" id="text_en" name="text_en" rows="10" >{{ old('text', $experience->text_en ) }}</textarea>
                        </div>
                    </div>
                    <div class="mb-3 p-3" style="background-color: #e6ffe6; border-radius: 8px;">
                        <div class="form-group">
                            <label for="text_ru">{{ __('Text of Experience') }} {{__('ru')}}</label>
                            <textarea class="form-control" id="text_ru" name="text_ru" rows="10" >{{ old('text', $experience->text_ru ) }}</textarea>
                        </div>
                    </div>


                    {{-- Actions & Equipments --}}
                    <div class="row mb-3 p-3" style="background-color: #fff0f5; border-radius: 8px;">
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label>{{ __('Actions') }}</label>
                                @foreach($actions as $action)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="actions[]" value="{{ $action->id }}" {{ in_array($action->id, $experience->actions->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $action->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label>{{ __('Equipments') }}</label>
                                @foreach($equipments as $equipment)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="equipments[]" value="{{ $equipment->id }}" {{ in_array($equipment->id, $experience->equipments->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                                <label>{{ __('Causes') }}</label>
                                @foreach($causes as $cause)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="causes[]" value="{{ $cause->id }}" {{ in_array($cause->id, $experience->reasons->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $cause->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6 border">
                            <div class="form-group">
                                <label for="systems">{{ __('Systems') }}</label>
                                <select class="form-control" id="systems" name="systems[]" size="8" multiple>
                                    @foreach($systems as $subsystem)
                                        <option value="{{ $subsystem->id }}" {{ in_array($subsystem->id, $experience->systems->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $subsystem->abv }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-primary w-100">{{ __('Update') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
