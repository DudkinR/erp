@extends('layouts.app')
@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Show')}}</h1>
                <a class="text-right" href="{{ route('projects.index') }}">{{__('Back')}}</a>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-12">
            <h1>{{__('Project')}}  {{$project->name}} </h1>
                <form method="POST" action="{{ route('projects.update',$project) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group mb-2">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{$project->name}}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="5" >{{$project->description}}</textarea>
                    </div>
                    <div class="form-group mb-2">
                        <label for="priority">{{__('Priority')}}</label>
                        <input type="number" class="form-control" id="priority" name="priority" value="{{$project->priority}}"> 
                    </div>
                    <div class="form-group mb-2">
                        <label for="number">{{__('Number')}}</label>
                        <input type="text" class="form-control" id="number" name="number" value="{{$project->number}}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="date">{{__('Date')}}</label>
                        <input type="date" class="form-control" id="date" name="date" value="{{$project->date}}">
                    </div>
                    <div class="form-group mb-2">
                        <label for="current_state">{{__('Current State')}}</label>
                        <select name="current_state" id="current_state" class="form-control">
                            <option value="Очікується погодження" @if($project->current_state == 'Очікується погодження') selected @endif>{{__('Очікується погодження')}}</option>
                            <option value="Готовий до забезпечення" @if($project->current_state == 'Готовий до забезпечення') selected @endif>{{__('Готовий до забезпечення')}}</option>
                            <option value="Виготовлення та комплектація" @if($project->current_state == 'Виготовлення та комплектація') selected @endif>{{__('Виготовлення та комплектація')}}</option> 
                            <option value="Готовий до відвантаження" @if($project->current_state == 'Готовий до відвантаження') selected @endif>{{__('Готовий до відвантаження')}}</option>
                            <option value="У процесі відвантаження" @if($project->current_state == 'У процесі відвантаження') selected @endif>{{__('У процесі відвантаження')}}</option>
                            <option value="Очікується оплата (після відвантаження)" @if($project->current_state == 'Очікується оплата (після відвантаження)') selected @endif>{{__('Очікується оплата (після відвантаження)')}}</option>
                            <option value="Готовий до закриття" @if($project->current_state == 'Готовий до закриття') selected @endif>{{__('Готовий до закриття')}}</option>
                            <option value="Закритий" @if($project->current_state == 'Закритий') selected @endif>{{__('Закритий')}}</option>
                            <option value="Чернетка" @if($project->current_state == 'Чернетка') selected @endif>{{__('Чернетка')}}</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="execution_period">{{__('Execution Period')}}</label>
                        <input type="date" class="form-control" id="execution_period" name="execution_period" value="{{$project->execution_period}}">
                    </div>

                    <button type="submit" class="btn btn-primary">{{__('Update')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection