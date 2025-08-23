@extends('layouts.app')

@section('content')
<div class="container py-4">
<div class="card shadow-lg border-0 rounded-3">
  <div class="card-header bg-primary text-white fw-bold">
    📊 Статистика документів
  </div>
  <ul class="list-group list-group-flush">
    <li class="list-group-item">🌍 Не перекладено: <span class="fw-bold">{{ $notTranslated }}</span></li>
    <li class="list-group-item">💾 Є електронні версії: <span class="fw-bold">{{ $withElectronic }}</span></li>
    <li class="list-group-item">📄 Не відскановано: <span class="fw-bold">{{ $notScanned }}</span></li>
    <li class="list-group-item">📅 Зареєстровані за поточний рік: <span class="fw-bold">{{ $byYear }}</span></li>
    <li class="list-group-item">🗓️ Зареєстровані за поточний місяць: <span class="fw-bold">{{ $byMonth }}</span></li>
    <li class="list-group-item">📦 З архівними номерами: <span class="fw-bold">{{ $withArchiveNumber }}</span></li>
    <li class="list-group-item">📂 Зареєстровані без архівних номерів: <span class="fw-bold">{{ $withoutArchiveNumber }}</span></li>
  </ul>
</div>


<canvas id="executorsChart"></canvas>
<canvas id="monthlyChart"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Виконавці
    new Chart(document.getElementById('executorsChart'), {
        type: 'bar',
        data: {
            labels: @json($byExecutors->pluck('kor')),
            datasets: [{
                label: 'Документи по виконавцях',
                data: @json($byExecutors->pluck('total'))
            }]
        }
    });

    // Динаміка по місяцях
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: @json($monthlyDynamics->map(fn($d) => $d->year.'-'.$d->month)),
            datasets: [{
                label: 'Кількість документів',
                data: @json($monthlyDynamics->pluck('total'))
            }]
        }
    });
</script>
@endsection
