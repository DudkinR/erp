@extends('layouts.app')
@section('content')
<div class="container">
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

    <div class="row">
        <div class="col-md-12">
            <h1>{{__('Team Create')}}</h1>
            <form method="POST" action="{{ route('teams.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">{{__('Name')}}</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">{{__('Description')}}</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>               

                <div class="mb-3">
                    <label for="users" class="form-label">{{__('Users')}}</label>

                    {{-- приклад для одного $boss --}}
                    @if(isset($boss))
                        <div class="form-check">
                            <input class="form-check-input user-checkbox" 
                                   type="checkbox" 
                                   value="{{ $boss->id }}" 
                                   id="user_{{ $boss->id }}" 
                                   name="users[]">
                            <label class="form-check-label" for="user_{{ $boss->id }}">
                                {{ $boss->name }}
                            </label>
                            {{__('(Roles)')}}
                            <input type="checkbox" value="{{ $boss->id }}" id="role_boss_{{ $boss->id }}" name="rolesb[]">
                            <label class="form-check-label" for="role_boss_{{ $boss->id }}">
                                {{ __('Admin') }}
                            </label>
                            <input type="checkbox" value="{{ $boss->id }}" id="role_member_{{ $boss->id }}" name="rolesm[]" class="role-member">
                            <label class="form-check-label" for="role_member_{{ $boss->id }}">
                                {{ __('Member') }}
                            </label>
                        </div>
                    @endif

                    {{-- приклад для $user --}}
                    @if(isset($user))
                        <div class="form-check">
                            <input class="form-check-input user-checkbox" 
                                   type="checkbox" 
                                   value="{{ $user->id }}" 
                                   id="user_{{ $user->id }}" 
                                   name="users[]">
                            <label class="form-check-label" for="user_{{ $user->id }}">
                                {{ $user->name }}
                            </label>
                            {{__('(Roles)')}}
                            <input type="checkbox" value="{{ $user->id }}" id="role_boss_{{ $user->id }}" name="rolesb[]">
                            <label class="form-check-label" for="role_boss_{{ $user->id }}">
                                {{ __('Admin') }}
                            </label>
                            <input type="checkbox" value="{{ $user->id }}" id="role_member_{{ $user->id }}" name="rolesm[]" class="role-member">
                            <label class="form-check-label" for="role_member_{{ $user->id }}">
                                {{ __('Member') }}
                            </label>
                        </div>
                    @endif

                    {{-- список інших користувачів --}}
                    @if(isset($relatedUsers))
                        @foreach($relatedUsers as $relatedUser)
                        <div class="form-check">
                            <input class="form-check-input user-checkbox" 
                                   type="checkbox" 
                                   value="{{ $relatedUser->id }}" 
                                   id="user_{{ $relatedUser->id }}" 
                                   name="users[]">
                            <label class="form-check-label" for="user_{{ $relatedUser->id }}">
                                {{ $relatedUser->name }} 
                            </label>
                            {{__('(Roles)')}}
                            <input type="checkbox" value="{{ $relatedUser->id }}" id="role_boss_{{ $relatedUser->id }}" name="rolesb[]">
                            <label class="form-check-label" for="role_boss_{{ $relatedUser->id }}">
                                {{ __('Admin') }}
                            </label>
                            <input type="checkbox" value="{{ $relatedUser->id }}" id="role_member_{{ $relatedUser->id }}" name="rolesm[]" class="role-member">
                            <label class="form-check-label" for="role_member_{{ $relatedUser->id }}">
                                {{ __('Member') }}
                            </label>
                        </div>
                        @endforeach
                    @endif

                </div>
                <div class="mb-3">
                    <label for="add_personal_tn" class="form-label">{{__('Add personal task notification')}}</label>
                    <input type="text" class="form-control" id="add_personal_tn" name="add_personal_tn" >
                </div>

                <button type="submit" class="btn btn-primary w-100">{{__('Create')}}</button>
            </form>
        </div>
    </div>
</div>

{{-- JavaScript --}}
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".user-checkbox").forEach(userCheckbox => {
        userCheckbox.addEventListener("change", function() {
            const userId = this.value;
            const memberCheckbox = document.getElementById("role_member_" + userId);
            if (this.checked) {
                memberCheckbox.checked = true;
            } else {
                memberCheckbox.checked = false;
            }
        });
    });
});
</script>
@endsection
