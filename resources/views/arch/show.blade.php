@extends('layouts.app')

@section('content')
@php
    $status =['active'=>'Діючий','canceled'=>'Анульований','replaced'=>'Замінений','draft'=>'Чернетка','other'=>'Інше'];
@endphp
<div class="container py-4">

    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0">
                📄 @if ($document)
                     {{ $document->national_name ?: $document->foreign_name }}
                @else
                     Документ не знайдено
                @endif
                <a href="{{ route('archived-documents.panel') }}" class="btn btn-light">Повернутися</a>
            </h4>
        </div>
        @if($document->relatedDocs->count())
            <div class="alert alert-info" role="alert">
                📄 Зв'язні документи:
                <ul>
                    @foreach($document->relatedDocs as $relatedDoc)
                        <li>
                            <a href="{{ route('archived-documents.show', $relatedDoc->id) }}">
                                {{ $relatedDoc->foreign_name ?: $relatedDoc->national_name }} ID: {{ $relatedDoc->id }} шифр: {{ $relatedDoc->code }} інв. №: {{ $relatedDoc->inventory }} арх. №: {{ $relatedDoc->archive_number }}
                                Статус: {{ $status[$relatedDoc->status] ?? 'Невідомий' }}                                
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($document->status === 'canceled')
            <div class="alert alert-warning" role="alert">
                📄 Документ анульований
                @php
                    $document->load('replacedBack');
                @endphp
                @if($document->replacedBack->count())
                    <br>📄 Замінений на:
                    <ul>
                        @foreach($document->replacedBack as $relatedDoc)
                            <li>
                                <a href="{{ route('archived-documents.show', $relatedDoc->id) }}">
                                    {{ $relatedDoc->foreign_name ?: $relatedDoc->national_name }} (ID: {{ $relatedDoc->id }}) (шифр: {{ $relatedDoc->code }}) (інв. №: {{ $relatedDoc->inventory }}) (арх. №: {{ $relatedDoc->archive_number }})
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        @endif
        <div class="card-body"  @if ($document->status === 'canceled') style="opacity: 0.5;" @endif>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Українська назва:</strong> {{ $document->national_name ?: '—' }}</p>
                    <p><strong>Іноземна назва:</strong> {{ $document->foreign_name ?: '—' }}</p>
                    <p><strong>Дата реєстрації:</strong> {{ $document->reg_date ?: '—' }}</p>
                    <p><strong>Дата в виробництві:</strong> {{ $document->production_date ?: '—' }}</p>
                    <p><strong>Виконавець:</strong> {{ $document->kor ?: '—' }}</p>
                    <p><strong>Розробник:</strong> {{ $document->developer ?: '—' }}</p>
                    <p><strong>Частина проекту:</strong> {{ $document->part ?: '—' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Договір:</strong> {{ $document->contract ?: '—' }}</p>
                    <p><strong>Підрозділ розробник:</strong> {{ $document->develop ?: '—' }}</p>
                    <p><strong>Організація розробник:</strong>{{ $document->develop ?: '—' }}</p>
                    <p><strong>Об'єкт:</strong> {{ $document->object ?: '—' }}</p>
                    <p><strong>Блок:</strong> {{ $document->unit ?: '—' }}</p>
                    <p><strong>Стадія:</strong> {{ $document->stage ?: '—' }}</p>
                </div>
            </div>

            <hr>

            <div class="mb-3">
                <p><strong>Інвентарний № розробника:</strong> {{ $document->inventory ?: '—' }}</p>
                <p><strong>Архівний № ХАЕС:</strong> {{ $document->archive_number ?: '—' }}</p>
                <p><strong>Шифр документа:</strong> {{ $document->code ?: '—' }}</p>
                <p><strong>Місце зберігання:</strong> {{ $document->storage_location ?: '—' }}</p>
                <p><strong>{{__('Pages')}}:</strong> {{ $document->pages ?: '0' }}</p>
                <p><strong>Службова записка:</strong> {{ $document->notes ?: '—' }}</p>

                <p><strong>Статус:</strong> {{ $status[$document->status] ?: '—' }}</p>
                <p><strong>ID:</strong> {{ $document->id ?: '?' }}</p>
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
    <a href="{{ route('archived-document.copy', $document->id) }}" class="btn btn-info">📄 Копіювати документ</a>

            <a href="{{ route('archived-documents.edit', $document->id) }}" class="btn btn-warning">✏️ Редагувати документ</a>
            @endif
            <a href="{{ route('archived-documents.index') }}" class="btn btn-secondary">⬅ Повернутись</a>
            <form action="{{ route('archived-document.destroy',  $document) }}" 
                    method="POST" 
                    class="d-inline"
                    onsubmit="return confirm('Ви впевнені, що хочете видалити цей документ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Видалити
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
