@extends('layouts.app')

@section('content')
<div class="container py-4"> 
    <a href="{{ route('archived-documents.panel') }}" class="btn btn-light">Повернутися</a>

    <form action="{{ route('archived-packages.update', $package->id) }}" method="POST" enctype="multipart/form-data" class="card shadow-lg border-0 rounded-4">
        @csrf
        @method('PUT')

        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">Редагувати пакет</h4>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="foreign_name" class="form-label"><strong>Назва (foreign_name):</strong></label>
                    <input type="text" name="foreign_name" id="foreign_name" class="form-control" value="{{ old('foreign_name', $package->foreign_name) }}" />

                    <label for="national_name" class="form-label mt-3"><strong>Українська назва:</strong></label>
                    <input type="text" name="national_name" id="national_name" class="form-control" value="{{ old('national_name', $package->national_name) }}" />

            </div>          
        <div class="card-footer text-end bg-light rounded-bottom-4">
            <a href="{{ route('archived-documents.packages.show', $package->id) }}" class="btn btn-secondary me-2">⬅ Повернутись</a>
            <button type="submit" class="btn btn-primary">💾 Зберегти зміни</button>
        </div>
    </form>

</div>
@endsection
