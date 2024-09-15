@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{__('Edit profile')}}</h1>
            <form method="POST" action="{{ route('profiles.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">{{__('Name')}}</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->profile->fio }}" required>
                    @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">{{__('Email')}}</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{__('Update profile')}}</button>
            </form>
        </div>
    </div>
</div>
@endsection