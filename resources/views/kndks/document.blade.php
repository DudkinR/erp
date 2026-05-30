@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">

            <!-- Кнопка повернення до пошуку -->
            <div class="mb-3">
                <a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">
                    ← Повернутися до пошуку
                </a>
            </div>

            <!-- Картка інформації про документ -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold">📄 Інформація про документ</h5>
                </div>
                <div class="card-body p-4">
                    <div class="p-3 bg-light rounded-3">
                        <h4 class="text-primary fw-bold mb-3">{{ $document->short_content }}</h4>
                        <div class="row g-3">
                            <div class="col-sm-6"><strong>Шифр:</strong> <code class="text-danger fw-bold fs-6">{{ $document->code ?? '-' }}</code></div>
                            <div class="col-sm-6"><strong>Інв. Номер:</strong> <span class="badge bg-secondary fs-6">{{ $document->inv_no }}</span></div>
                            <div class="col-sm-6"><strong>Організація:</strong> {{ $document->organization ?? '-' }}</div>
                            <div class="col-sm-6"><strong>Вид документа:</strong> {{ $document->doc_type ?? '-' }}</div>
                            <div class="col-sm-12"><strong>Дата реєстрації:</strong> {{ $document->registration_date ?? '-' }}</div>
                            
                            <!-- НОВИЙ БЛОК: Зв'язані елементи класифікатора КНДК -->
                            <div class="col-sm-12 mt-3 border-top pt-3">
                                <strong class="d-block mb-2 text-muted small text-uppercase">Зв'язані сфери / напрями класифікатора (КНДК):</strong>
                                @if($document->kndks && $document->kndks->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($document->kndks as $kndk)
                                            <a href="{{ route('kndks.show', $kndk->id) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center rounded-pill px-3 shadow-sm" title="{{ $kndk->name }}">
                                                <span class="badge bg-primary me-2 font-monospace">{{ $kndk->full_code }}</span>
                                                <span class="text-truncate" style="max-width: 250px;">{{ $kndk->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">Немає прямих прив'язок до класифікатора КНДК</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Блок зауважень -->
                    <div class="d-flex align-items-center mb-3 text-danger">
                        <h5 class="fw-bold mb-0">⚠️ Невідповідності та зауваження ({{ $inconsistencies->count() }})</h5>
                    </div>

                    @if($inconsistencies->isEmpty())
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <span>Зауважень чи невідповідностей до цього документа не знайдено.</span>
                        </div>
                    @else
                        @foreach($inconsistencies as $inc)
                            @php
                                $statusClass = 'bg-warning text-dark';
                                if($inc->status === 'fixed') $statusClass = 'bg-success text-white';
                                if($inc->status === 'return') $statusClass = 'bg-danger text-white';
                            @endphp
                            
                            <div class="card border border-light-subtle shadow-sm p-3 mb-4 rounded-3 bg-white" id="inc-card-{{ $inc->id }}">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                    <div><strong>Пункт вимоги/стандарту:</strong> <span class="text-primary fw-bold">{{ $inc->point ?? '-' }}</span></div>
                                    <span class="badge {{ $statusClass }} px-3 py-1.5 fs-7">Статус: {{ $inc->status }}</span>
                                </div>
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="p-2 bg-danger-subtle bg-opacity-10 border border-danger-subtle rounded text-secondary h-100">
                                            <small class="text-danger d-block fw-bold mb-1">❌ Поточна редакція:</small>
                                            {!! nl2br(e($inc->current_text)) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-2 bg-success-subtle bg-opacity-10 border border-success-subtle rounded text-dark h-100">
                                            <small class="text-success d-block fw-bold mb-1">✔️ Запропонована редакція:</small>
                                            {!! nl2br(e($inc->proposed_text)) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 p-2 bg-light rounded text-muted small">
                                    <strong>Обґрунтування/Причина:</strong> {{ $inc->reason ?? '-' }}
                                </div>

                                <!-- Форма відповіді перевіряючого -->
                                <form id="qaForm-{{ $inc->id }}" class="bg-light p-3 rounded-3 border">
                                    <div class="row g-2">
                                        <div class="col-md-4 mb-2">
                                            <label class="form-label small fw-bold">Прийняти рішення:</label>
                                            <select class="form-select form-select-sm" name="action">
                                                <option value="fixed">Виконано (Затвердити)</option>
                                                <option value="return">Повернути до СЯ (На доопрацювання)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8 mb-2">
                                            <label class="form-label small fw-bold">Обґрунтування відповіді або коментар:</label>
                                            <textarea class="form-control form-control-sm" name="comment" rows="2" placeholder="Залиште коментар для авторів..."></textarea>
                                        </div>
                                    </div>
                                    <div class="text-end mt-2">
                                        <button type="button" class="btn btn-success btn-sm px-4 fw-bold" onclick="sendQaResult({{ $inc->id }})">
                                            💾 Відправити рішення
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function sendQaResult(incId) {
    const form = document.getElementById(`qaForm-${incId}`);
    const action = form.querySelector('[name="action"]').value;
    const comment = form.querySelector('[name="comment"]').value;

    fetch('/inconsistencies/process', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            inconsistency_id: incId,
            action: action,
            comment: comment
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Рішення успішно збережено!');
            const card = document.getElementById(`inc-card-${incId}`);
            if (card) {
                card.style.opacity = '0.6';
                form.innerHTML = '<p class="text-success fw-bold mb-0">✓ Відповідь успішно надіслана сервером.</p>';
            }
        } else {
            alert('Помилка: ' + (data.message || 'Не вдалося зберегти рішення.'));
        }
    })
    .catch(err => {
        console.error('Помилка:', err);
        alert('Сталася помилка при надсиланні запиту.');
    });
}
</script>
@endsection
