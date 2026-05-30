@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        {{-- Виведення помилок валідації --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Повідомлення про успіх --}}
        @if(session('success'))
            <div class="alert alert-success mb-3">{{ __(session('success')) }}</div>
        @endif

        {{-- Повідомлення про помилку --}}
        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ __(session('error')) }}</div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">
                    {{ __('kndk_docs') }}: {{ __('Завантаження для КНДК') }} № {{ $kndk->full_code ?? $kndk->id }}
                </h4>
            </div>
            <div class="card-body">
                {{-- У маршрут передається ID конкретного КНДК --}}
                <form method="POST" action="{{ route('kndks.importData', $kndk->id) }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="import_file" class="form-label fw-bold">{{ __('Оберіть CSV файл з документами') }}</label>
                        <input type="file" 
                               class="form-control @error('import_file') is-invalid @enderror" 
                               id="import_file" 
                               name="import_file" 
                               accept=".csv, .txt" 
                               required>
                        <div class="form-text">
                            {{ __('Файл має містити колонку з індивідуальним номером документа (Inv. No) для зв\'язку.') }}
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('/kndks') }}" class="btn btn-secondary">
                            {{ __('Назад до списку') }}
                        </a>
                        <button type="submit" class="btn btn-success px-4">
                            {{ __('Завантажити та обробити') }}
                        </button>
                    </div>
                </form>
            </div>
              <a href="/kndks/{{$kndk->id +1 }}/import-page" class="btn btn-outline-secondary border-0" title="Імпорт">
                                          next
                                        </a>
        </div>
    </div>
@endsection
