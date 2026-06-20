@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- Помилки валідації --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success mb-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error --}}
    @if(session('error'))
        <div class="alert alert-danger mb-3">
            {{ session('error') }}
        </div>
    @endif


    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                Імпорт КНДК документів

                @if(isset($kndk))
                    — {{ $kndk->full_code ?? $kndk->id }} 
                   -  {{ $kndk->name }} 
                @endif
            </h5>
        </div>

        <div class="card-body">

            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ isset($kndk)
                        ? route('kndks.DocsimportData', $kndk->id)
                        : route('kndks.importData') }}">

                @csrf

                {{-- FILE INPUT --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Оберіть CSV файл
                    </label>

                    <input type="file"
                           name="import_file"
                           class="form-control @error('import_file') is-invalid @enderror"
                           accept=".csv,.txt"
                           required>

                    @error('import_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                    <div class="form-text">
                        Файл має бути у форматі CSV з розділювачем ";"
                    </div>
                </div>


                {{-- BUTTONS --}}
                <div class="d-flex justify-content-between">

                    <a href="{{ url('/kndks') }}"
                       class="btn btn-secondary">
                        ← Назад
                    </a>

                    <button type="submit"
                            class="btn btn-success px-4">
                        Завантажити та імпортувати
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>
@endsection