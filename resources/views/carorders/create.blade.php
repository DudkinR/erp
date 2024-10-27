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
                <h1>{{__('Carorders')}}</h1>
                <form method="POST" action="{{ route('carorders.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="Title">{{__('Title of work')}}</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">{{__('Description of work')}}</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="type_id">{{__('Type of car')}}</label>
                        <select class="form-control" id="type_id" name="type_id" required>
                            @php  
                              $parent_car_type = $all_types->firstWhere('slug', 'Typ-transportnoho');

                                $car_types = collect(); // Створення порожньої колекції за замовчуванням

                                if ($parent_car_type) {
                                    $car_types = $all_types->filter(function($type) use ($parent_car_type) {
                                        return $type->parent_id === $parent_car_type->id;
                                    });
                                }
                            @endphp
                            @foreach($car_types as $car_type)
                                <option value="{{ $car_type->id }}"
                                    @if(isset($_GET['type_id']) && $_GET['type_id'] == $car_type->id) selected @endif>{{ $car_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="value">{{__('Value')}}</label>
                        <input type="text" class="form-control" id="value" name="value" required>
                    </div>
                    <div class="form-group">
                        <label for="value_type_id">{{__('Value Type')}}</label>
                        <select class="form-control" id="value_type_id" name="value_type_id" required>
                            @php
                            $parent_value_type = $all_types->firstWhere('slug', 'Typ-znachennya');
                            $value_types = collect(); // Створення порожньої колекції за замовчуванням
                            if($parent_value_type) {
                                $value_types = $all_types->filter(function($type) use ($parent_value_type) {
                                    return $type->parent_id === $parent_value_type->id;
                                });
                            }
                            @endphp
                            @foreach($value_types as $value_type)
                                <option value="{{ $value_type->id }}"
                                    @if(isset($_GET['value_type_id']) && $_GET['value_type_id'] == $value_type->id) selected @endif>{{ $value_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status')}}</label>
                        <select class="form-control" id="status" name="status" required>                            
                                <option value="Normal">{{__('Normal')}}</option>
                                <option value="Urgent">{{__('Urgent')}}</option>
                                <option value="Emergency">{{__('Emergency')}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Start_datetime">{{__('Start datetime')}}</label>
                        <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" required>
                    </div>
                    <div class="form-group">
                        <label for="End_datetime">{{__('End datetime')}}</label>
                        <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" required>
                    </div>
                    <div class="form-group">
                        <label for="Hours">{{__('Hours')}}</label>
                        <input type="number" class="form-control" id="hours" name="hours" required>
                    </div>
                    <div class="form-group">
                        <label for="Googlemap_start_point">{{__('Googlemap start point')}}</label>
                        <input type="text" class="form-control" id="start_point" name="start_point" required>
                    </div>
                    <div class="form-group">
                        <label for="Googlemap_end_point">{{__('Googlemap end point')}}</label>
                        <input type="text" class="form-control" id="end_point" name="end_point" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection