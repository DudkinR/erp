@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Повідомлення про помилки --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Flash повідомлення --}}
    @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-shield-check me-2"></i> {{ __('Протідія') }}
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('briefs.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name_uk" class="form-label">{{ __('Name (Ukrainian)') }}</label>
                            <input type="text" class="form-control" id="name_uk" name="name_uk" value="{{ old('name_uk') }}" placeholder="Введіть назву українською">
                        </div>

                        <div class="mb-3">
                            <label for="name_ru" class="form-label">{{ __('Name (Russian)') }}</label>
                            <input type="text" class="form-control" id="name_ru" name="name_ru" value="{{ old('name_ru') }}" placeholder="Введите название на русском">
                        </div>

                        <div class="mb-3">
                            <label for="name_en" class="form-label">{{ __('Name (English)') }}</label>
                            <input type="text" class="form-control" id="name_en" name="name_en" value="{{ old('name_en') }}" placeholder="Enter name in English">
                        </div>

                        <div class="mb-3">
                            <label for="order" class="form-label">Порядок</label>
                            <input type="number" class="form-control" id="order" name="order" value="{{ old('order') }}" placeholder="Наприклад: 1">
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }}</label>
                            <select class="form-select" id="type" name="type">
                                <option value="0">{{ __('Select Type') }}</option>
                                <option value="0" {{ old('type') == '0' ? 'selected' : '' }}>Протідія</option>
                                <option value="1" {{ old('type') == '1' ? 'selected' : '' }}>Тема інструктажа до роботи</option>
                                <option value="2" {{ old('type') == '2' ? 'selected' : '' }}>Тема інструктажа під час роботи</option>
                                <option value="3" {{ old('type') == '3' ? 'selected' : '' }}>Тема інструктажа після роботи</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="risk" class="form-label">{{ __('Risk') }} 0-7</label>
                            <select class="form-select" id="risk" name="risk">
                                <option value="0">{{ __('Select Risk') }}</option>
                                <option value="1" {{ old('risk') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('risk') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ old('risk') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('risk') == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ old('risk') == '5' ? 'selected' : '' }}>5</option>
                                <option value="6" {{ old('risk') == '6' ? 'selected' : '' }}>6</option>
                                <option value="7" {{ old('risk') == '7' ? 'selected' : '' }}>7</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="functional" class="form-label">{{ __('Functional') }} Ефектівність 1-7</label>
                            <select class="form-select" id="functional" name="functional">
                                <option value="0">{{ __('Select Functional') }}</option>
                                <option value="1" {{ old('functional') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('functional') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ old('functional') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('functional') == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ old('functional') == '5' ? 'selected' : '' }}>5</option>
                                <option value="6" {{ old('functional') == '6' ? 'selected' : '' }}>6</option>
                                <option value="7" {{ old('functional') == '7' ? 'selected' : '' }}>7</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-save me-2"></i> {{ __('Save Brief') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
