@extends('layouts.app')
@section('content')
<div class="container">

    <h2 class="mb-4">Деталі роботи</h2>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="card-title">{{ $job->basis }} ({{ $job->type }})</h4>
            <p><strong>Будівля:</strong> {{ $job->build }}</p>
            <p><strong>Приміщення:</strong> {{ $job->room }}</p>
            <p><strong>Вісі/координати:</strong> {{ $job->location_axes }}</p>
            <p><strong>Елемент:</strong> {{ $job->element }}</p>
            <p><strong>Вид робіт:</strong> {{ $job->work_type }}</p>
            <p><strong>Одиниця:</strong> {{ $job->unit }}</p>
            <p><strong>Кількість:</strong> {{ $job->q }}</p>
            <p><strong>Людино‑години (план):</strong> {{ $job->whh }}</p>
            <p><strong>Матеріали:</strong> {{ $job->tmc }}</p>
            <p><strong>Цех:</strong> {{ $job->own_division }}</p>
            <p><strong>Локальні примітки:</strong> {{ $job->note_locale }}</p>
            <p><strong>Примітки:</strong> {{ $job->note }}</p>
            <p><strong>Грант/код:</strong> {{ $job->grant }}</p>
        </div>
    </div>

    <!-- Форма для внесення виконаної роботи -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Внести виконану роботу</h5>
            <form action="{{ route('constructionjobs.addmonth', $job->id) }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Місяць</label>
                        <select name="month" class="form-select" required>
                            <option value="">-- виберіть місяць --</option>
                            @foreach(['jan'=>'Січень','feb'=>'Лютий','mar'=>'Березень','apr'=>'Квітень','may'=>'Травень','jun'=>'Червень','jul'=>'Липень','aug'=>'Серпень','sep'=>'Вересень','oct'=>'Жовтень','nov'=>'Листопад','dec'=>'Грудень'] as $m=>$label)
                                <option value="{{ $m }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Фактичні людино‑години</label>
                        <input type="number" step="0.01" name="real_value" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Зберегти виконання</button>
            </form>
        </div>
    </div>

    <!-- Аналітика -->
    <h3>Аналітика</h3>
    <div class="row">
        <div class="col-md-6 mb-4">
            <canvas id="monthsChart"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="compareChart"></canvas>
        </div>
        <div class="col-md-12 mb-4">
            <canvas id="deviationChart"></canvas>
        </div>
    </div>

    <a href="{{ route('constructionjobs.index') }}" class="btn btn-secondary mt-3">Назад</a>
    <a href="{{ route('constructionjobs.edit', $job->id) }}" class="btn btn-warning mt-3">Редагувати</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const months = {
        'Січень': {{ $job->jan ?? 0 }},
        'Лютий': {{ $job->feb ?? 0 }},
        'Березень': {{ $job->mar ?? 0 }},
        'Квітень': {{ $job->apr ?? 0 }},
        'Травень': {{ $job->may ?? 0 }},
        'Червень': {{ $job->jun ?? 0 }},
        'Липень': {{ $job->jul ?? 0 }},
        'Серпень': {{ $job->aug ?? 0 }},
        'Вересень': {{ $job->sep ?? 0 }},
        'Жовтень': {{ $job->oct ?? 0 }},
        'Листопад': {{ $job->nov ?? 0 }},
        'Грудень': {{ $job->dec ?? 0 }},
    };

    const realMonths = {
        'Січень': {{ $job->real_jan ?? 0 }},
        'Лютий': {{ $job->real_feb ?? 0 }},
        'Березень': {{ $job->real_mar ?? 0 }},
        'Квітень': {{ $job->real_apr ?? 0 }},
        'Травень': {{ $job->real_may ?? 0 }},
        'Червень': {{ $job->real_jun ?? 0 }},
        'Липень': {{ $job->real_jul ?? 0 }},
        'Серпень': {{ $job->real_aug ?? 0 }},
        'Вересень': {{ $job->real_sep ?? 0 }},
        'Жовтень': {{ $job->real_oct ?? 0 }},
        'Листопад': {{ $job->real_nov ?? 0 }},
        'Грудень': {{ $job->real_dec ?? 0 }},
    };

    const sumMonths = Object.values(months).reduce((a,b)=>a+b,0);
    const whh = {{ $job->whh ?? 0 }};
    const deviation = whh - sumMonths;

    // Графік по місяцях: план vs факт
    new Chart(document.getElementById('monthsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(months),
            datasets: [
                {
                    label: 'Планові людино‑години',
                    data: Object.values(months),
                    backgroundColor: '#4e79a7'
                },
                {
                    label: 'Фактичні людино‑години',
                    data: Object.values(realMonths),
                    backgroundColor: '#e15759'
                }
            ]
        },
        options: {
            plugins: { title: { display: true, text: 'Розподіл по місяцях (план vs факт)' } },
            responsive: true
        }
    });

    // Порівняння WHH vs сума місяців
    new Chart(document.getElementById('compareChart'), {
        type: 'doughnut',
        data: {
            labels: ['WHH', 'Сума місяців'],
            datasets: [{
                data: [whh, sumMonths],
                backgroundColor: ['#e15759','#76b7b2']
            }]
        },
        options: {
            plugins: { title: { display: true, text: 'Порівняння WHH та суми місяців' } }
        }
    });

    // Відхилення
    new Chart(document.getElementById('deviationChart'), {
        type: 'bar',
        data: {
            labels: ['Відхилення'],
            datasets: [{
                label: 'WHH - сума місяців',
                data: [deviation],
                backgroundColor: deviation > 0 ? '#e15759' : (deviation < 0 ? '#f28e2b' : '#59a14f')
            }]
        },
        options: {
            plugins: { title: { display: true, text: 'Відхилення WHH від суми місяців' } },
            responsive: true
        }
    });
});

</script>
@endsection
