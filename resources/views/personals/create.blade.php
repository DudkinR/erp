@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Create Personal')}}</h1>
                <a href="{{ route('personal.index') }}" class="btn btn-secondary">{{__('Back')}}</a>
                <form method="POST" action="{{ route('personal.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="tn">{{__('tn')}}</label>
                        <input type="text" class="form-control" id="tn" name="tn" value="">
                    </div>
                    <div class="form-group">
                        <label for="fio">{{__('FIO')}}</label>
                        <input type="text" class="form-control" id="fio" name="fio" value="">
                    </div>
                    <div class="form-group">
                        <label for="email">{{__('Email')}}</label>
                        <input type="email" class="form-control" id="email" name="email" value="">
                    </div>
                    <div class="form-group">
                        <label for="phone">{{__('Phone')}}</label>
                        <input type="phone" class="form-control" id="phone" name="phone" value="">
                    </div>
                    <div class="form-group">
                        <label for="position">{{__('Position')}}</label>
                        <?php
                         $positions = App\Models\Position::orderBy('id', 'desc')->get(); ?>
                        <select class="form-control" id="position" name="position">
                            @foreach($positions as $position)
                                <option value="{{ $position->id }}" >{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">{{__('Status')}}</label>
                        <?php $statuses = [ 'На роботі', 'Відпустка','Звілнений', 'Лікарняний']; ?>
                        <select class="form-control" id="status" name="status">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" >{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_start">{{__('Date start')}}</label>
                        <input type="date" class="form-control" id="date_start" name="date_start" value="">
                    </div>
                    <div class="form-group">
                        <label for="date_end">{{__('Date end')}}</label>
                        <input type="date" class="form-control" id="date_end" name="date_end" value="">
                    </div>
                    <div class="form-group">
                        <label for="comment">{{__('Comment')}}</label>
                        <textarea class="form-control" id="comment" name="comment"></textarea>
                    </div>
                    <div class="form-group">
                        <?php $roles = App\Models\Role::orderBy('id', 'desc')->get(); ?>
                        <label for="roles">{{__('Roles')}}</label>
                        <select class="form-control" id="roles" name="roles[]" multiple>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" >{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection