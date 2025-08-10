@extends('layouts.app')

@section('content')
<div class="container py-4">

    <form action="{{ route('archived-documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="card shadow-lg border-0 rounded-4">
        @csrf
        @method('PUT')

        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">Редагувати документ</h4>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="foreign_name" class="form-label"><strong>Назва (foreign_name):</strong></label>
                    <input type="text" name="foreign_name" id="foreign_name" class="form-control" value="{{ old('foreign_name', $document->foreign_name) }}" />

                    <label for="national_name" class="form-label mt-3"><strong>Національна назва:</strong></label>
                    <input type="text" name="national_name" id="national_name" class="form-control" value="{{ old('national_name', $document->national_name) }}" />

                    <label for="reg_date" class="form-label mt-3"><strong>Дата реєстрації:</strong></label>
                    <input type="date" name="reg_date" id="reg_date" class="form-control" value="{{ old('reg_date', $document->reg_date) }}" />

                    <label for="production_date" class="form-label mt-3"><strong>Дата виготовлення:</strong></label>
                    <input type="date" name="production_date" id="production_date" class="form-control" value="{{ old('production_date', $document->production_date) }}" />

                    <label for="kor" class="form-label mt-3"><strong>Виконавець (КОР):</strong></label>
                    <input type="text" name="kor" id="kor" class="form-control" value="{{ old('kor', $document->kor) }}" />

                    <label for="part" class="form-label mt-3"><strong>Частина:</strong></label>
                    <input type="text" name="part" id="part" class="form-control" value="{{ old('part', $document->part) }}" />
                </div>

                <div class="col-md-6">
                    <label for="contract" class="form-label"><strong>Договір:</strong></label>
                    <input type="text" name="contract" id="contract" class="form-control" value="{{ old('contract', $document->contract) }}" />

                    <label for="develop" class="form-label mt-3"><strong>Розробник:</strong></label>
                    <input type="text" name="develop" id="develop" class="form-control" value="{{ old('develop', $document->develop) }}" />

                    <label for="object" class="form-label mt-3"><strong>Об'єкт:</strong></label>
                    <input type="text" name="object" id="object" class="form-control" value="{{ old('object', $document->object) }}" />

                    <label for="unit" class="form-label mt-3"><strong>Блок:</strong></label>
                    <input type="text" name="unit" id="unit" class="form-control" value="{{ old('unit', $document->unit) }}" />

                    <label for="stage" class="form-label mt-3"><strong>Стадія:</strong></label>
                    <input type="text" name="stage" id="stage" class="form-control" value="{{ old('stage', $document->stage) }}" />
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <label for="inventory" class="form-label"><strong>Інвентарний номер:</strong></label>
                <input type="text" name="inventory" id="inventory" class="form-control" value="{{ old('inventory', $document->inventory) }}" />

                <label for="code" class="form-label mt-3"><strong>Код:</strong></label>
                <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $document->code) }}" />

                <label for="storage_location" class="form-label mt-3"><strong>Місце зберігання:</strong></label>
                <input type="text" name="storage_location" id="storage_location" class="form-control" value="{{ old('storage_location', $document->storage_location) }}" />

                <label for="path" class="form-label mt-3"><strong>Файл (оновити):</strong></label>
                <input type="file" name="path" id="path" class="form-control" />
                @if($document->path)
                    <p class="mt-2">
                        Поточний файл: 
                        <a href="{{ asset($document->path) }}" target="_blank">Відкрити документ</a>
                    </p>
                @endif
            </div>

            <hr>

            <h5>📦 Пакети</h5>
            @if($document->packages->count())
                <ul class="list-group">
                    @foreach($document->packages as $package)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <input type="text" name="packages[{{ $loop->index }}][foreign_name]" class="form-control form-control-sm mb-1" value="{{ old('packages.' . $loop->index . '.foreign_name', $package->foreign_name) }}" placeholder="Назва пакета" />
                                <input type="text" name="packages[{{ $loop->index }}][national_name]" class="form-control form-control-sm" value="{{ old('packages.' . $loop->index . '.national_name', $package->national_name) }}" placeholder="Національна назва" />
                            </div>
                            <input type="hidden" name="packages[{{ $loop->index }}][id]" value="{{ $package->id }}">
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Пакети відсутні.</p>
            @endif

        </div>

        <div class="card-footer text-end bg-light rounded-bottom-4">
            <a href="{{ route('archived-documents.show', $document->id) }}" class="btn btn-secondary me-2">⬅ Повернутись</a>
            <button type="submit" class="btn btn-primary">💾 Зберегти зміни</button>
        </div>
    </form>

</div>
@endsection
