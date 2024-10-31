@extends('layouts.print')
@section('content')
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
            $overtimeCallings = [];
            $weekendCallings = [];
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
                    }
                    if($vtype['parent_id'] == 4) {
                        // Аналогічно для вихідних днів
                        $weekendCallings = array_unique(array_merge($weekendCallings, $callings->where('type_id', $vtype['id'])->pluck('start_time')->all()));
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
            1.1. Забезпечити нарахування заробітної плати в {{ $overtimenextMonth }} за роботу у надурочний час {{ implode(', ', $formattedOvertimeCallings) }} в подвійному розмірі та 40% від посадового окладу (місячної тарифної ставки) за роботу в нічні години такому персоналу, а саме:
        @else
            Даних про роботу у надурочний час не знайдено.
        @endif 
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
            </tbody>
        </table>

        </div>
    </div>
     <div class="row">
             <p>
             1.2. Забезпечити нарахування заробітної плати в жовтні 2024 року за роботу у вихідні дні 14.09.2024, 20.09.2024, 23.09.2024, 27.09.2024, 28.09.2024, 29.09.2024, в подвійному розмірі та 40% від посадового окладу (місячної тарифної ставки) за роботу в нічні години такому персоналу, а саме:  
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
            </tbody>
        </table>

        </div>
    </div>
    <div class="row">
             <p>
             1.3. Забезпечити нарахування заробітної плати в жовтні 2024 року 22.09.2024, 25.09.2024, 28.09.2024 за фактично відпрацьований час в одинарному розмірі за роботу у вихідний день за надання іншого дня відпочинку такому персоналу, а саме:
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
            </tbody>
        </table>

        </div>
    </div>
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