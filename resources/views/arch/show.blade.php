@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">
                📄 {{ $document->foreign_name ?: 'Без назви' }}
            </h4>
        </div>

        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Українська назва:</strong> {{ $document->national_name ?: '—' }}</p>
                    <p><strong>Дата реєстрації:</strong> {{ $document->reg_date ?: '—' }}</p>
                    <p><strong>Дата виготовлення:</strong> {{ $document->production_date ?: '—' }}</p>
                    <p><strong>Виконавець (КОР):</strong> {{ $document->kor ?: '—' }}</p>
                    <p><strong>Частина:</strong> {{ $document->part ?: '—' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Договір:</strong> {{ $document->contract ?: '—' }}</p>
                    <p><strong>Розробник:</strong> {{ $document->develop ?: '—' }}</p>
                    <p><strong>Об'єкт:</strong> {{ $document->object ?: '—' }}</p>
                    <p><strong>Блок:</strong> {{ $document->unit ?: '—' }}</p>
                    <p><strong>Стадія:</strong> {{ $document->stage ?: '—' }}</p>
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <p><strong>Інвентарний номер:</strong> {{ $document->inventory ?: '—' }}</p>
                <p><strong>Код:</strong> {{ $document->code ?: '—' }}</p>
                <p><strong>Місце зберігання:</strong> {{ $document->storage_location ?: '—' }}</p>
                <p><strong>{{__('Pages')}}:</strong> {{ $document->pages ?: '0' }}</p>
                @if($document->path)
                    <p>
                        <strong>Файл:</strong>
                        <a href="{{ asset($document->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            📂 Відкрити документ
                        </a>
                    </p>
                @endif
            </div>

            <hr>

            <h5>📦 Пакети</h5>
            @if($document->packages->count())
                <ul class="list-group">
                    @foreach($document->packages as $package)
                        <li class="list-group-item">
                            <strong>{{ $package->foreign_name }}</strong>
                            @if($package->national_name)
                                <br><small>{{ $package->national_name }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">Пакети відсутні.</p>
            @endif
        </div>

        <div class="card-footer text-end bg-light rounded-bottom-4">
             @if(Auth::user()->hasRole('quality-engineer','admin'))
            <a href="{{ route('archived-documents.edit', $document->id) }}" class="btn btn-warning">✏️ Редагувати документ</a>
            @endif
            <a href="{{ route('archived-documents.index') }}" class="btn btn-secondary">⬅ Повернутись</a>
        </div>
    </div>

</div>
@endsection
