@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4 text-center text-primary">📊 Результати оцінки ризиків</h2>

    <div class="card shadow-lg border-info">
        <div class="card-body">

            @php
                // групуємо події за work_type
                $grouped = [];
                foreach($events as $id => $event) {
                    $grouped[$event['work_type']][] = $event;
                }
            @endphp

            <table class="table table-hover table-bordered align-middle shadow-sm">
                <thead class="table-info text-center">
                    <tr>
                        <th>Група</th>
                        <th>Подія</th>
                        <th>Т (Severity)</th>
                        <th>І (Probability)</th>
                        <th>Ч (Frequency)</th>
                        <th>R</th>
                        <th>Категорія</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grouped as $workType => $groupEvents)
                        @php
                            $groupRisk = 0;
                            $count = count($groupEvents);
                        @endphp
                        @foreach($groupEvents as $index => $event)
                            @php
                                $severity = (float)$event['severity'];
                                $probability = (float)$event['probability'];
                                $frequency = (float)$event['frequency'];
                                $R = $severity * $probability * $frequency;

                                if ($R <= 7) { $category = 'Прийнятний'; $rowClass = ''; }
                                elseif ($R <= 20) { $category = 'Допустимий'; $rowClass = 'table-warning'; }
                                else { $category = 'Значний'; $rowClass = 'table-danger'; }

                                $groupRisk += $R;
                            @endphp
                            <tr class="{{ $rowClass }}">
                                @if($index === 0)
                                    <td rowspan="{{ $count }}" class="align-middle fw-bold text-info">
                                        {{ $workType }}
                                    </td>
                                @endif
                                <td>{{ $event['name'] }}</td>
                                <td class="text-center">{{ number_format($severity, 2) }}</td>
                                <td class="text-center">{{ number_format($probability, 2) }}</td>
                                <td class="text-center">{{ number_format($frequency, 2) }}</td>
                                <td class="text-center fw-bold">{{ number_format($R, 2) }}</td>
                                <td class="text-center">{{ $category }}</td>
                            </tr>
                        @endforeach

                        @php
                            $avgRisk = $count > 0 ? $groupRisk / $count : 0;
                            if ($avgRisk <= 7) { $summary = 'Прийнятний'; $class = 'table-info'; }
                            elseif ($avgRisk <= 20) { $summary = 'Допустимий'; $class = 'table-warning'; }
                            else { $summary = 'Значний'; $class = 'table-danger'; }
                        @endphp
                        <tr class="{{ $class }} fw-bold">
                            <td colspan="5" class="text-end">Підсумок для групи:</td>
                            <td class="text-center">{{ number_format($avgRisk, 2) }}</td>
                            <td class="text-center">{{ $summary }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
