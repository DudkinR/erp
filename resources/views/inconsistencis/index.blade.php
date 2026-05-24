@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">{{ __('Невідповідності') }}</h1>
            <a class="btn btn-primary mb-4" href="{{ route('inconsistencis.create') }}">
                <i class="bi bi-plus-circle"></i> {{ __('Створити нову') }}
            </a>
        </div>
    </div>

    {{-- Мої невідповідності --}}

    <div class="card mb-4 shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Мої зауваження</h5>
    </div>
    <div class="card-body">
        @forelse($userInconsistencies as $inc)
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><strong>Пункт:</strong> {{ $inc->point }}</span>
                    <span class="badge 
                        @if($inc->status === 'approved') bg-success
                        @elseif($inc->status === 'rejected') bg-danger
                        @else bg-warning
                        @endif">
                        {{ ucfirst($inc->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold mb-2">
                        {{ $inc->documents->first()->short_content ?? 'Документ' }}
                    </h6>
                    <p class="mb-1">
                        <span class="badge bg-secondary">Шифр: {{ $inc->documents->first()->code ?? '-' }}</span>
                        <span class="badge bg-info">Організація: {{ $inc->documents->first()->organization ?? '-' }}</span>
                    </p>
                    <p><strong>Причина:</strong> {{ $inc->reason }}</p>
                    <p><strong>Стара редакція:</strong> {{ $inc->current_text }}</p>
                    <p><strong>Запропонована редакція:</strong> {{ $inc->proposed_text }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">Ви ще не створили жодної невідповідності.</p>
        @endforelse
    </div>
</div>



   {{-- Для служби якості --}}
@if(Auth::user()->hasRole('quality-engineer'))
<div class="card mb-4 shadow-sm">
    <div class="card-header bg-warning">
        <h5 class="mb-0">Зауваження на розгляд</h5>
    </div>
    <div class="card-body">
      
        @forelse($qaInconsistencies as $inc)
            <div class="border rounded p-3 mb-3">
                <h6 class="fw-bold">
                    {{ $inc->documents->first()->short_content ?? 'Документ' }}
                </h6>
                <p class="mb-1">
                    <span class="badge bg-secondary">Шифр: {{ $inc->documents->first()->code ?? '-' }}</span>
                    <span class="badge bg-info">Організація: {{ $inc->documents->first()->organization ?? '-' }}</span>
                </p>

                <p><strong>Пункт:</strong> {{ $inc->point }}</p>
                <p><strong>Стара редакція:</strong> {{ $inc->current_text }}</p>
                <p>
                    <strong>Запропонована редакція:</strong>
                    <span id="proposed-text-{{ $inc->id }}">
                        {{ $inc->proposed_text }}
                    </span>
                </p>
                <p><strong>Причина:</strong> {{ $inc->reason }}</p>

                <div class="d-flex justify-content-between mt-3">
                    <button class="btn btn-outline-primary btn-sm"
        onclick="openEditModal({{ $inc->id }}, @js($inc->proposed_text))">
                        <i class="bi bi-pencil-square"></i> Змінити запропонований текст
                    </button>

                    <button class="btn btn-success btn-sm"
                            onclick="approveInconsistency({{ $inc->id }})">
                        <i class="bi bi-check-circle"></i> Погодити
                    </button>

                    <button class="btn btn-danger btn-sm"
                            onclick="rejectInconsistency({{ $inc->id }})">
                        <i class="bi bi-x-circle"></i> Відмова
                    </button>
                </div>
            </div>
        @empty
            <p class="text-muted">Немає нових зауважень для перевірки.</p>
        @endforelse
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editModalLabel">Редагувати запропонований текст</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="editForm">
          @csrf
          <input type="hidden" id="edit_inconsistency_id" name="id">
          <div class="mb-3">
            <textarea class="form-control" id="edit_proposed_text" name="proposed_text" rows="4"></textarea>
          </div>
          <button type="button" class="btn btn-success w-100" onclick="saveEdit()">Зберегти</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endif


    {{-- Для автора документа --}}

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Зауваження до моїх документів</h5>
        </div>
        <div class="card-body">
            @forelse($authorInconsistencies as $inc)
                <p>
                    <strong>{{ $inc->documents->first()->short_content ?? 'Документ' }}</strong> —
                    {{ $inc->proposed_text }}
                    <span class="badge bg-secondary ms-2">Зауважень: {{ $inc->authorResponses->count() }}</span>
                </p>
            @empty
                <p class="text-muted">До ваших документів наразі немає зауважень.</p>
            @endforelse
        </div>
    </div>


</div>

<script>

    // локальне сховище змінених текстів
    let editedTexts = {};

    // bootstrap modal instance
    let modal;

    document.addEventListener('DOMContentLoaded', function () {

        modal = new bootstrap.Modal(
            document.getElementById('editModal')
        );
    });


    function openEditModal(id, text) {

        document.getElementById(
            'edit_inconsistency_id'
        ).value = id;

        // якщо вже редагували — беремо локальний текст
        document.getElementById(
            'edit_proposed_text'
        ).value = editedTexts[id] ?? text;

        modal.show();
    }


    function saveEdit() {

        let id = document.getElementById(
            'edit_inconsistency_id'
        ).value;

        let text = document.getElementById(
            'edit_proposed_text'
        ).value;

        // зберігаємо локально
        editedTexts[id] = text;

        // оновлюємо текст на сторінці
        document.getElementById(
            'proposed-text-' + id
        ).innerText = text;

        modal.hide();
    }


    function approveInconsistency(id) {

        // беремо локально змінений текст
        let text =
            editedTexts[id]
            ?? document.getElementById(
                'proposed-text-' + id
            ).innerText;

        let url =
            "{{ route('inconsistencis.approve', ':id') }}"
            .replace(':id', id);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN':
                    '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                proposed_text: text
            })
        })
        .then(res => res.json())
        .then(data => {

            alert('Зауваження погоджено!');
            location.reload();
        })
        .catch(error => {

            console.error(error);
            alert('Помилка збереження');
        });
    }


    function rejectInconsistency(id) {

        let url =
            "{{ route('inconsistencis.reject', ':id') }}"
            .replace(':id', id);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':
                    '{{ csrf_token() }}'
            }
        })
        .then(() => {

            alert('Відмовлено!');
            location.reload();
        });
    }

</script>

@endsection
