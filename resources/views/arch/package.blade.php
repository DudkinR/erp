@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Картка пакета --}}
    <div class="card shadow-lg border-0 rounded-4 mb-4">
        <div class="card-header bg-success text-white rounded-top-4">
            <h4 class="mb-0">📦 {{ $package->foreign_name ?: 'Без назви' }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Національна назва:</strong> {{ $package->national_name ?: '—' }}</p>
            <p><strong>Створено:</strong> {{ \Carbon\Carbon::parse($package->created_at)->format('d.m.Y H:i') }}</p>
        </div>
    </div>

    {{-- Таблиця документів --}}
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-light rounded-top-4">
            <h5 class="mb-0">📄 Документи пакета</h5>
        </div>
        <div class="card-body p-0">
            @if($package->documents->count())
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th>Назва</th>
                                <th>Національна назва</th>
                                <th>Дата реєстрації</th>
                                <th>Код</th>
                                <th>Інвентарний</th>
                                <th>КОР</th>
                                <th>Об'єкт</th>
                                <th>Стадія</th>
                                <th class="text-center">Файл</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($package->documents as $doc)
                                <tr>
                                    <td>{{ $doc->foreign_name ?: '—' }}</td>
                                    <td
                                    @if($doc->national_name=='') class="bg-warning" @endif
                                    >{{ $doc->national_name ?: '—' }}</td>
                                    <td>{{ $doc->reg_date ?: '—' }}</td>
                                    <td>{{ $doc->code ?: '—' }}</td>
                                    <td>{{ $doc->inventory ?: '—' }}</td>
                                    <td>{{ $doc->kor ?: '—' }}</td>
                                    <td>{{ $doc->object ?: '—' }}</td>
                                    <td>{{ $doc->stage ?: '—' }}</td>
                                    <td class="text-center">
                                        @if($doc->path)
                                            <a href="{{ asset($doc->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                📂
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-3 text-muted">Документи відсутні.</div>
            @endif
        </div>
        <div class="card-footer text-end bg-light rounded-bottom-4">
            
            <a href="{{ route('archived-documents.packages.edit', $package->id) }}" class="btn btn-warning">✏️ Редагувати пакет</a>
            
            <a href="{{ route('archived-documents.packages') }}" class="btn btn-secondary">⬅ Повернутись</a>
        </div>
    </div>

</div>
@endsection
