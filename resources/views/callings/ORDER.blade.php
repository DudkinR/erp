@extends('layouts.print')
@section('content')
@php
use Carbon\Carbon;
    // Функція для підрахунку годин
    function count_time($start, $finish)
{
    /*
    Считаем сколько часов если более 8 часов вычитаем 1 час обеда 
    также высчитываем с этого времени ночное время с 22 до 6  
    */
    // Перетворюємо строки у об'єкти Carbon
    $start = \Carbon\Carbon::parse($start);
    $finish = \Carbon\Carbon::parse($finish);

    // Загальний час між початком і кінцем
    $total_time = abs($finish->diffInHours($start));
    
    // Визначаємо нічний час
    $total_night_time = 0;
    
    // Період з 22:00 до 6:00 наступного дня
    $start_night = $start->copy()->setTime(22, 0);  // Початок ночного часу (22:00)
    $finish_night = $start->copy()->setTime(6, 0)->addDay();  // Кінець нічного часу (6:00 наступного дня)
    
    // Рахуємо нічний час
    if ($start < $finish_night && $finish > $start_night) {
        $night_start = $start->max($start_night);
        $night_end = $finish->min($finish_night);
        $total_night_time = abs($night_end->diffInHours($night_start));
    }

    // Format values to two decimal places
    return [
        'total_time' => number_format($total_time, 2),
        'total_night_time' => number_format($total_night_time, 2)
    ];
}
function formatCallings($callings)
{
    $cl = [];
    foreach ($callings as $calling) {
        $date = Carbon::parse($calling->start_time)->format('d.m.Y');
        $cl[$date][] = $calling->id;
    }

    // Sort dates
    ksort($cl);

    $formattedResults = [];

    foreach ($cl as $date => $numbers) {
        sort($numbers); // Sort numbers for each date to find consecutive sequences
        $formattedNumbers = [];
        $rangeStart = $numbers[0];
        $previous = $rangeStart;

        for ($i = 1; $i < count($numbers); $i++) {
            if ($numbers[$i] == $previous + 1) {
                // If consecutive, move to next
                $previous = $numbers[$i];
            } else {
                // If not consecutive, add range or single number
                $formattedNumbers[] = ($rangeStart == $previous) ? $rangeStart : "$rangeStart-$previous";
                $rangeStart = $numbers[$i];
                $previous = $rangeStart;
            }
        }
        // Add the last range
        $formattedNumbers[] = ($rangeStart == $previous) ? $rangeStart : "$rangeStart-$previous";

        // Combine date and formatted numbers
        $formattedResults[] = "від {$date} №" . implode(', ', $formattedNumbers);
    }

    // Join all date groups with a semicolon
    return "Бланки виклику на роботу: " . implode("; ", $formattedResults);
}
@endphp
<!-- very siple page for print -->
<div class="container">
<div class="row">
        <div class="col-md-12">
        <img src="{{ asset('logo/sequence.png') }}" >
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h1>НАКАЗ</h1>
            <p>
             Про оплату роботи за викликом
            </p>
        </div>
    </div>
    <div class="row">
    <div class="col-md-12">
        <p>
        @php
            // Перетворення $callings у колекцію
            $callings = collect($callings);
            $workerOvertimeCallings = collect(); // Initialize as an empty collection
            $workerWeekendCallings = collect(); // Initialize as an empty collection
            $overtimeCallings = [];
            $weekendCallings = [];
            $freeCallings = [];
            $freeDays = [];
            $n=1;
        @endphp

        @foreach($DI['Vyklyk_na_robotu_ids'] as $Vyklyk_na_robotu_id)
            @php
                // Фільтрування $DI['all_types'] по parent_id
                $vtypes = collect($DI['all_types'])->filter(function ($type) use ($Vyklyk_na_robotu_id) {
                    return $type['parent_id'] == $Vyklyk_na_robotu_id->id;
                });

                foreach($vtypes as $vtype) {
                    if($vtype['parent_id'] == 2) {
                        // Збираємо дати, використовуючи pluck, а потім видаляємо дублікати
                        $overtimeCallings = array_unique(array_merge($overtimeCallings, $callings->where('type_id', $vtype['id'])->pluck('start_time')->all()));
                        $workerOvertimeCallings = $workerOvertimeCallings->merge($callings->where('type_id', $vtype['id']));
  
                    }
                    if($vtype['parent_id'] == 4) {
                        // Аналогічно для вихідних днів
                        $weekendCallings = array_unique(array_merge($weekendCallings, $callings->where('type_id', $vtype['id'])->pluck('start_time')->all()));
                        $workerWeekendCallings =  $workerWeekendCallings->merge($callings->where('type_id', $vtype['id']));
                    }
                }
            @endphp
        @endforeach

        {{-- Форматування дат перед виведенням --}}
        @php
            $formattedOvertimeCallings = array_unique(array_map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d.m.Y');
            }, $overtimeCallings));
            // Сортування від малого до великого
            sort($formattedOvertimeCallings);
            $formattedWeekendCallings = array_unique(array_map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d.m.Y');
            }, $weekendCallings));
            // Сортування від малого до великого
            sort($formattedWeekendCallings);
            $overtimemaxDate = !empty($overtimeCallings) ? \Carbon\Carbon::parse(max($overtimeCallings)) : null;
            $overtimenextMonth = $overtimemaxDate ? $overtimemaxDate->copy()->addMonth()->translatedFormat('F Y') : '';
  
        @endphp

        {{-- Виведення тексту на основі наявних дат --}}
        @if(!empty($formattedOvertimeCallings) && !empty($formattedWeekendCallings))
            На підставі бланків виклику на роботу у надурочний час:
            {{ implode(', ', $formattedOvertimeCallings) }} 
            та у вихідні дні:
            {{ implode(', ', $formattedWeekendCallings) }}.
        @elseif(!empty($formattedOvertimeCallings))
            На підставі бланків виклику на роботу у надурочний час:
            {{ implode(', ', $formattedOvertimeCallings) }}.
        @elseif(!empty($formattedWeekendCallings))
            На підставі бланків виклику на роботу у вихідні дні:
            {{ implode(', ', $formattedWeekendCallings) }}.
        @else
            Немає бланків виклику на роботу у надурочний час або у вихідні дні.
        @endif
        </p>
    </div>
</div>

    
    <div class="row">
        <div class="col-md-12">
             <h1>НАКАЗУЮ:</h1>
             <p>1. Головному бухгалтеру Мельничук А. А.:</p>
             <p>
             {{-- Виведення тексту з обчисленим місяцем --}}
        @if(!empty($formattedOvertimeCallings))
            1.{{$n}}. Забезпечити нарахування заробітної плати в {{ $overtimenextMonth }} за роботу у надурочний час {{ implode(', ', $formattedOvertimeCallings) }} в подвійному розмірі та 40% від посадового окладу (місячної тарифної ставки) за роботу в нічні години такому персоналу, а саме:
            @php
                $n++;
            @endphp
        </p>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                Назва підрозділа:
                <table border="1" width="100%">
                    <thead>
                        <tr>
                            <!-- First 3 columns span 2 rows -->
                            <th rowspan="2">Прізвище ім’я по батькові</th>
                            <th rowspan="2">Посада</th>
                            <th rowspan="2">Таб. №</th>
                            
                            <!-- Last 2 columns span across 2 columns in the second row -->
                            <th colspan="2">Кількість годин</th>
                        </tr>
                        <tr>
                            <!-- Sub-columns for total hours and night hours -->
                            <th>всього</th>
                            <th>в. т. ч. нічні</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Example row for data -->
                        <tr>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
                            <td>4</td>
                            <td>5</td>
                        </tr>
                        @foreach($workerOvertimeCallings as $worker)
                            @php
                                $time = count_time($worker->start_time, $worker->finish_time);
                            @endphp
                            <tr>
                                <td>{{ $worker->worker->fio }}</td>
                                <td>{{ $worker->worker->positions[0]->name }}</td>
                                <td>{{ $worker->worker->tn }}</td>
                                <td>{{ $time['total_time'] }}</td>
                                <td>{{ $time['total_night_time'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                </div>
            </div>
     
        @endif 
        @if(!empty($formattedWeekendCallings))
            <div class="row">
                <div class="col-md-12">
                <p>
                1.{{$n}}. Забезпечити нарахування заробітної плати в {{ $overtimenextMonth }} за роботу у вихідні дні {{ implode(', ', $formattedWeekendCallings) }} в подвійному розмірі та 40% від посадового окладу (місячної тарифної ставки) за роботу в нічні години такому персоналу, а саме:
                @php
                    $n++;
                @endphp
                </p>
            </div>
        </div>
        <div class="row">
        <div class="col-md-12">
        Назва підрозділа:
        <table border="1" width="100%">
            <thead>
                <tr>
                    <!-- First 3 columns span 2 rows -->
                    <th rowspan="2">Прізвище ім’я по батькові</th>
                    <th rowspan="2">Посада</th>
                    <th rowspan="2">Таб. №</th>
                    
                    <!-- Last 2 columns span across 2 columns in the second row -->
                    <th colspan="2">Кількість годин</th>
                </tr>
                <tr>
                    <!-- Sub-columns for total hours and night hours -->
                    <th>всього</th>
                    <th>в. т. ч. нічні</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example row for data -->
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                </tr>
                @php
                    $freePayWorkings = [];
                    $freeDayWorkings= [];
                @endphp
                @foreach($workerWeekendCallings as $call)
                    @foreach($call->workers as $worker) 
                        @php

                        $time = count_time($worker->start_time, $worker->finish_time);
                        if($worker->payment_type_id==90){
                            if(!isset($freeDayWorkings[$worker->tn]))
                            $freeDayWorkings[$worker->tn] = 
                            [ "tn" => $worker->tn,
                            "fio" => $worker->fio,
                            "positions" => $worker->positions[0]->name,
                            "total_time" => $time['total_time'],
                            "total_night_time" => $time['total_night_time']
                            ];
                            else{
                                $freeDayWorkings[$worker->tn]['total_time'] += $time['total_time'];
                                $freeDayWorkings[$worker->tn]['total_night_time'] += $time['total_night_time'];
                            }
                            $freeCallings[] = $call->start_time;
                        }
                        else{
                            if(!isset($freePayWorkings[$worker->tn]))
                            $freePayWorkings[$worker->tn] = 
                            [ "tn" => $worker->tn,
                            "fio" => $worker->fio,
                            "positions" => $worker->positions[0]->name,
                            "total_time" => $time['total_time'],
                            "total_night_time" => $time['total_night_time']
                            ];
                            else{
                                $freePayWorkings[$worker->tn]['total_time'] += $time['total_time'];
                                $freePayWorkings[$worker->tn]['total_night_time'] += $time['total_night_time'];
                            }
                        }
                        @endphp
                    @endforeach
                @endforeach
                @foreach($freePayWorkings as $worker)
                    <tr>
                        <td>{{ $worker['fio'] }}</td>
                        <td>{{ $worker['positions']}}</td>
                        <td>{{ $worker['tn'] }}</td>
                        <td>{{ $worker['total_time'] }}</td>
                        <td>{{ $worker['total_night_time'] }}</td>
                    </tr>
                @endforeach
                
            </tbody>
        </table>

        </div>
    </div>
        @endif
        @if(!empty($freeDayWorkings))
         @php 
        // format date
        $formattedFreeDays = array_unique(array_map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d.m.Y');
        }, $freeDays));
        // sort date
        sort($formattedFreeDays);
        // unique date
        $freeDayWorkings = array_unique($freeDayWorkings);

    @endphp
    @endif
    @if(!empty($freeDayWorkings))
    @php
        $n++;
        //format date $freeCallings
        $formattedFreeDays = array_unique(array_map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d.m.Y');
        }, $freeCallings));
        //sort date
        sort($formattedFreeDays);
        // unique date
        $formattedFreeDays = array_unique($formattedFreeDays);

    @endphp
    <div class="row">
             <p>
             1.{{$n}}. Забезпечити нарахування заробітної плати в 
                {{ $overtimenextMonth }} за роботу у вихідні дні 
                {{ implode(', ', $formattedFreeDays) }} за фактично відпрацьований час в одинарному розмірі за роботу у вихідний день за надання іншого дня відпочинку такому персоналу, а саме:
             </p>
    </div> 
    <div class="row">
        <div class="col-md-12">
        Назва підрозділа:
        <table border="1" width="100%">
            <thead>
                <tr>
                    <!-- First 3 columns span 2 rows -->
                    <th rowspan="2">Прізвище ім’я по батькові</th>
                    <th rowspan="2">Посада</th>
                    <th rowspan="2">Таб. №</th>
                    
                    <!-- Last 2 columns span across 2 columns in the second row -->
                    <th colspan="2">Кількість годин</th>
                </tr>
                <tr>
                    <!-- Sub-columns for total hours and night hours -->
                    <th>всього</th>
                    <th>в. т. ч. нічні</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example row for data -->
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                </tr>
                @foreach($freeDayWorkings as $worker)
              
                    <tr>
                        <td>{{ $worker['fio'] }}</td>
                        <td>{{ $worker['positions']}}</td>
                        <td>{{ $worker['tn'] }}</td>
                        <td>{{ $worker['total_time'] }}</td>
                        <td>{{ $worker['total_night_time'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12">
             <p>
             2. Витрати з оплати праці віднести за місцем виникнення основної та додаткової заробітної плати.
            </p>
        </div>
        <div class="row">
            <div class="col-md-2">
            Підстави: 
            </div>   
            <div class="col-md-10">
                <ul>
                    <ol>
                        {{ formatCallings($callings) }}
                    </ol>
                    <ol>
                        Бланки виклику на роботу: від 12.09.2024 №426; від 13.09.2024 №444; від 14.09.2024 №450, 458; від 19.09.2024 №491; від 20.09.2024 №505; від 21.09.2024 №516; від 22.09.2024 №549, 556; від 23.09.2024 №557, 558, 561, 562, 573; від 24.09.2024 №574-577, 579, 580, 582, 583, 584, 586; від 25.09.2024 №588, 591-598, 600; від 26.09.2024 №602, 604, 605, 606, 607; від 27.09.2024 №609, 611-625, 673; від 28.09.2024 №626-632; від 29.09.2024 №633-638; від 30.09.2024 №639, 640, 645-649, 651-655, 657-660.
                    </ol>
                    <ol>
                        Положення про порядок виклику персоналу на роботу в надурочний час, у вихідні, святкові і неробочі дні, компенсації й оплати за роботу за викликами 0.ТЗ.2812.ПЛ-21.
                    </ol>
                    <ol>
                        Положення про порядок планування та використання коштів на оплату праці працівників ДП «НАЕК «Енергоатом» ПЛ-К.0.24.376-18.
                    </ol>
                </ul>
            </div>    
        </div>
    </div> 
    <div class="row">
        <div class="col-md-6">
            <#тут_буде_посада_підписувача#>
        </div>   
        <div class="col-md-10">
            <#тут_буде_ПІБ_підписувача#>
        </div>   
    </div>
    <div class="row">
        <div class="col-md-12">
            <p>
            Розсилка 21, 22, 23, 24, 25, 26, 28, 32, 36, 39, 42, 48, 49, 51. 
            </p>
            <p>
            ВОНтаОП Євгеній Вербіцький 6 28 95
            </p>
        </div>   
    </div>  
</div>
<script>
    const Workings=@json($Workings);
    console.log(Workings);
</script>
@endsection