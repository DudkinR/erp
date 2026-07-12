@extends('layouts.app')
@section('content')

<style>
    .vertical {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        text-align: center;
    }
</style>
<div class="container">

    <h2>Річна статистика чоловіко‑годин</h2>


    <a href="{{ route('construction_jobs') }}" class="btn btn-primary mb-3 w-100">Скачати CSV</a>
    <a href="{{ route('construction_jobs_csv') }}" class="btn btn-primary mb-3 w-100">Завантажити CSV</a>
    
    <canvas id="yearChart"></canvas>

    <h2>По цехах</h2>
    <canvas id="divisionChart"></canvas>

    <h2>По об’єктах</h2>
    <canvas id="basisChart"></canvas>
   <div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Загальний WHH</h5>
                <p class="card-text fw-bold">{{ $totalWhh }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Середній WHH</h5>
                <p class="card-text fw-bold">{{ round($avgWhh,1) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Кількість робіт</h5>
                <p class="card-text fw-bold">{{ $jobs->count() }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Помилки WHH</h5>
                <p class="card-text fw-bold">{{ $invalidWhhCount }} ({{ round($invalidWhhCount/$jobs->count()*100,1) }}%)</p>
            </div>
        </div>
    </div>
</div>
 
    <a href="{{ route('constructionjobs.create') }}" class="btn btn-primary mb-3 w-100">Створити нову роботу</a>
    <h2>Всі дані</h2>
    <input type="text" id="searchInput" placeholder="Пошук...">
    <table id="jobsTable" class="table table-striped table-hover align-middle">
        <table id="jobsTable" class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>№</th>
                    <th>Об’єкт</th>
                    <th>Цех</th>
                    <th>План WHH</th>
                    <th>Сума місяців</th>
                    <th>Факт WHH</th>
                    <th>Відхилення</th>
                    <th>Виконання %</th>
                    <th>Дії</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $index => $job)
                @php
                    $months = ['jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];
                    $sumMonths = collect($months)->sum(fn($m) => $job->$m ?? 0);
                    $realSum = collect($months)->sum(fn($m) => $job->{'real_'.$m} ?? 0);
                    $deviation = $realSum - $sumMonths;
                    $percent = $job->whh > 0 ? round(($job->real_whh / $job->whh) * 100, 1) : 0;

                    $statusClass = $deviation > 0 ? 'bg-danger text-white' : ($deviation < 0 ? 'bg-warning text-dark' : 'bg-success text-white');
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $job->basis }}
                        {{ $job->build }}
                        {{ $job->room }}
                        {{ $job->location_axes }}
                        {{ $job->element }}
                        {{ $job->work_type }}
                        {{ $job->unit }}
                    </td>
                    <td>{{ $job->own_division }}</td>
                    <td>{{ $job->whh }}</td>
                    <td>{{ $sumMonths }}</td>
                    <td>{{ $job->real_whh }}</td>
                    <td class="{{ $statusClass }}">{{ $deviation }}</td>
                    <td>
                        <span class="badge {{ $percent >= 100 ? 'bg-success' : 'bg-warning' }}">
                            {{ $percent }}%
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('constructionjobs.show', $job->id) }}" class="btn btn-sm btn-info">Деталі</a>
                        <a href="{{ route('constructionjobs.edit', $job->id) }}" class="btn btn-sm btn-warning">✏️</a>
                        <form action="{{ route('constructionjobs.destroy', $job->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Видалити цю роботу?')">🗑️</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>


</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const stats = @json($stats);
    const divisionStats = @json($divisionStats);
    const basisStats = @json($basisStats);

    document.addEventListener("DOMContentLoaded", function() {
        // Загальний графік
        new Chart(document.getElementById('yearChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(stats),
                datasets: [{
                    label: 'Чоловіко‑години',
                    data: Object.values(stats),
                    backgroundColor: '#4e79a7'
                }]
            }
        });

        // По цехах не дуже презентабельно забагат  цехів а там ще є і заходи (назви)
        new Chart(document.getElementById('divisionChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(stats), // місяці
                datasets: Object.keys(divisionStats).map((div, i) => ({
                    label: div,
                    data: Object.values(divisionStats[div]),
                    backgroundColor: ['#4e79a7','#f28e2b','#e15759','#76b7b2','#59a14f'][i % 5]
                }))
            },
            options: {
                plugins: { title: { display: true, text: 'Розподіл по цехах' } },
                responsive: true,
                scales: { x: { stacked: true }, y: { stacked: true } }
            }
        });


        // По об’єктах не дуже показательно забагато об’єктів
        new Chart(document.getElementById('basisChart'), {
            type: 'bar',
            data: {
                labels: Object.keys(basisStats),
                datasets: Object.keys(basisStats).map((basis, i) => ({
                    label: basis,
                    data: Object.values(basisStats[basis]),
                    backgroundColor: ['#76b7b2','#59a14f','#edc949'][i % 3]
                }))
            }
        });

        // Пошук по таблиці
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#jobsTable tbody tr');
            rows.forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
            });
        });
    });


    

</script>
@endsection
