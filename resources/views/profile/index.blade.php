@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-5">{{ __('
                Профіль користувача') }}</h1>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <p><strong>{{ __('Електронна пошта') }}:</strong> {{ $user->email }}</p>

            @if(isset($user->profile->fio))
                <p><strong>{{ __('ПІБ') }}:</strong> {{ $user->profile->fio }}</p>
            @endif

            @if(isset($user->profile->positions))
                <p><strong>{{ __('Посади') }}:</strong></p>
                <ul>
                    @foreach($user->profile->positions as $position)
                        <li>{{ $position->name }}</li>
                    @endforeach
                </ul>
            @endif

            @if(isset($user->roles))
                <p><strong>{{ __('Ролі') }}:</strong></p>
                <ul>
                    @foreach($user->roles as $role)
                        <li>
                            {{ $role->name }} — <em>{{ $role->slug }}</em>
                            @if(Auth::user()->hasRole($role->slug))
                                <br><span class="text-success">{{ __('Ви маєте цю роль') }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 d-flex gap-3">
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                {{ __('Змінити пароль') }}
            </button>
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#reportIssueModal">
                {{ __('Повідомити про невідповідність') }}
            </button>
        </div>
    </div>

    {{-- Модальне вікно для зміни пароля --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordLabel">{{ __('Зміна пароля') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                         @method('PUT')
                       
                         <input type="hidden" name="email" value="{{ $user->email }}"> 
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('Поточний пароль') }}</label>
                            <input type="password" class="form-control" id="current_password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">{{ __('Новий пароль') }}</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">{{ __('Підтвердження пароля') }}</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('Зберегти зміни') }}</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Модальне вікно для повідомлення про невідповідність --}}
    <div class="modal fade" id="reportIssueModal" tabindex="-1" aria-labelledby="reportIssueLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                        <h5 class="modal-title" id="reportIssueLabel">{{ __('Повідомити про невідповідність') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрити"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="">
                            @csrf
                            <div class="mb-3">
                                <label for="issue_description" class="form-label">{{ __('Опишіть проблему') }}</label>
                                <textarea class="form-control" id="issue_description" name="issue_description" rows="4" required></textarea>
                            </div>
     
                            <button type="submit" class="btn btn-warning">{{ __('Надіслати повідомлення') }}</button>
                        </form>
                    </div>

            </div>
        </div>
    </div>
</div>
@endsection
