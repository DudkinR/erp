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
                <h1>{{__('Cars')}}</h1>
                <form method="POST" action="{{ route('cars.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="type_id">{{__('Type')}}</label>
                         @php  
                             
                            $parent_car_type = $all_types->firstWhere('slug', 'Typ-transportnoho');

                                $car_types = collect(); // Створення порожньої колекції за замовчуванням

                                if ($parent_car_type) {
                                    $car_types = $all_types->filter(function($type) use ($parent_car_type) {
                                        return $type->parent_id == $parent_car_type->id;
                                    });
                                }

                            @endphp
                        <select class="form-control" id="type_id" name="type_id" required>
                           
                            @foreach($car_types as $car_type)
                                <option value="{{ $car_type->id }}"
                                    @if(isset($_GET['type_id']) && $_GET['type_id'] == $car_type->id) selected @endif>{{ $car_type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="gov_number">{{__('Gov Number')}}</label>
                        <input type="text" class="form-control" id="gov_number" name="gov_number" required>
                    </div>
                    <div class="form-group">
                        <label for="condition_id">{{__('Condition')}}</label>
                        <select class="form-control" id="condition_id" name="condition_id" required>
                            @php
                            $parent_condition = $all_types->firstWhere('slug', 'Kondytsyya-transportnoho');
                            $conditions = collect(); // Створення порожньої колекції за замовчуванням
                            if($parent_condition) {
                                $conditions = $all_types->filter(function($type) use ($parent_condition) {
                                    return $type->parent_id === $parent_condition->id;
                                });
                            }

                            
                        @endphp

                            @foreach($conditions as $condition)
                                <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection