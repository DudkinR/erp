@extends('layouts.app')
@section('content')

<div class="container">
    <h1 class="mb-4"> {{__('EPM Data Analytics by Areas')}} </h1>
<a href="{{ route('epmdata') }}" class="btn btn-primary mb-4 w-100"> {{__('Back to Data')}} </a>
    <div class="row">
        @foreach($area_data as $area_name => $data)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header text-center">
                        <h5
                        title = "{{ $area_title[$area_name] }}" 
                        >{{ $area_name }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <div style="height: 150px;">
                            <canvas id="chart-{{ Str::slug($area_name) }}" style="max-height: 150px;"></canvas>
                        </div>
                        @php
                            $values = array_values($data['values'] ?? []); // Приводим к индексированному массиву
                            $count = count($values);
                            // Проверяем, если последний элемент пустой — убираем его
                            if ($count > 0 && empty($values[$count - 1])) {
                                array_pop($values);
                                $count = count($values); // Обновляем количество элементов
                            }
                            // Если после удаления меньше 2 значений, дублируем первое значение
                            $prelast = $count > 1 ? $values[$count - 2] : ($values[0] ?? 0);
                            $last = $count > 0 ? $values[$count - 1] : $prelast;
                            $trend = $last > $prelast ? '⬆ Зростає' : ($last < $prelast ? '⬇ Зменшується' : '➡ Стабільно');
                        @endphp
                        <p class="text-center mt-2">Тренд:
                            <strong>{{ $trend }}</strong>                          
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    @foreach($area_data as $area_name => $data)
        new Chart(document.getElementById("chart-{{ Str::slug($area_name) }}"), {
            type: 'line',
            data: {
                labels: @json($data['dates']),
                datasets: [{
                    label: "{{ $area_name }}",
                    data: @json($data['values']),
                    borderColor: 'blue',
                    borderWidth: 2,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        ticks: { autoSkip: true, maxTicksLimit: 5 }
                    }
                }
            }
        });
    @endforeach
});
</script>

@endsection
