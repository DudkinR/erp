@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ __('Team Edit') }}</h1>
    {{-- повідомлення про помилки --}}
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
    <a href="{{ route('teams.index') }}" class="btn btn-secondary">{{ __('Back to Teams') }}</a>
    <form method="POST" action="{{ route('teams.update', $team->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ old('name', $team->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $team->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">{{ __('Users') }}</label>
                @foreach($users as $u)
                <div class="form-check">
                    <input class="form-check-input user-checkbox"
                        type="checkbox"
                        value="{{ $u->id }}"
                        id="user_{{ $u->id }}"
                        name="users[]"
                        {{ isset($teamUsers[$u->id]) ? 'checked' : '' }}>
                    <label class="form-check-label" for="user_{{ $u->id }}">
                        {{ $u->name }}
                    </label>
                    {{ __('(Roles)') }}

                    <input type="checkbox"
                        value="{{ $u->id }}"
                        id="role_admin_{{ $u->id }}"
                        name="rolesb[]"
                        {{ (isset($teamUsers[$u->id]) && $teamUsers[$u->id] === 'admin') ? 'checked' : '' }}>
                    <label class="form-check-label" for="role_admin_{{ $u->id }}">
                        {{ __('Admin') }}
                    </label>

                    <input type="checkbox"
                        value="{{ $u->id }}"
                        id="role_member_{{ $u->id }}"
                        name="rolesm[]"
                        {{ (isset($teamUsers[$u->id]) && $teamUsers[$u->id] === 'member') ? 'checked' : '' }}>
                    <label class="form-check-label" for="role_member_{{ $u->id }}">
                        {{ __('Member') }}
                    </label>
                </div>
                @endforeach

        </div>
        <div class="mb-3">
            <label for="add_personal_tn" class="form-label">{{__('Add personal task notification')}}</label>
            <input type="text" class="form-control" id="add_personal_tn" name="add_personal_tn" >
        </div>
        <button type="submit" class="btn btn-primary w-100">{{ __('Update') }}</button>
    </form>
</div>
@endsection
