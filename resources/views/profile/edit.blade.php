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
                    <label for="name">{{__('pib')}}</label>
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
                <div class="form-group">
                    <label for="old_password">{{__('Old Password')}}</label>
                    <input type="password" name="old_password" id="old_password" class="form-control" required>
                    @error('old_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="new_password">{{__('New Password')}}</label>
                    <input type="password" name="new_password" id="new_password" class="form-control">
                    @error('new_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="new_password_confirmation">{{__('Confirm New Password')}}</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
                    @error('new_password_confirmation')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">{{__('Update profile')}}</button>
            </form>
        </div>
    </div>
</div>
<script>
    const new_password = document.getElementById('new_password');
    const new_password_confirmation = document.getElementById('new_password_confirmation');

    new_password_confirmation.addEventListener('input', function() {
        if (new_password.value !== new_password_confirmation.value) {
            new_password_confirmation.setCustomValidity("Passwords do not match");
        } else {
            new_password_confirmation.setCustomValidity("");
        }
    });
    // check before confirm
    new_password.addEventListener('input', function() {
        if (new_password.value !== new_password_confirmation.value) {
            new_password_confirmation.setCustomValidity("Passwords do not match");
        } else {
            new_password_confirmation.setCustomValidity("");
        }
    });
    
</script>
@endsection