@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{__('Log in')}}</h1>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{__('Email')}}</label>
                    <input type="text" name="email" id="email" class="form-control"  autofocus>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">{{__('Password')}}</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    @error('password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label">{{__('Remember me')}}</label>
                </div>
                <button type="submit" class="btn btn-primary">{{__('Log in')}}</button>
            </form>
        </div>
    </div>
</div>
@endsection