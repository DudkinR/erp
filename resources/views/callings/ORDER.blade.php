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
        На підставі бланків виклику на роботу у надурочний час
         12.09.2024, 13.09.2024, 14.09.2024, 19.09.2024, 21.09.2024, 23.09.2024, 24.09.2024, 25.09.2024, 26.09.2024, 27.09.2024, 28.09.2024, 30.09.2024
         та у вихідні дні 
         14.09.2024, 20.09.2024, 22.09.2024, 23.09.2024, 25.09.2024, 27.09.2024, 28.09.2024, 30.09.2024
         </p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
             <h1>НАКАЗУЮ:</h1>
             <p>1. Головному бухгалтеру Мельничук А. А.:</p>
             <p>
	1.1. Забезпечити нарахування заробітної плати в жовтні 2024 року за роботу у надурочний час 12.09.2024, 13.09.2024, 14.09.2024, 19.09.2024, 21.09.2024, 23.09.2024, 24.09.2024, 25.09.2024, 26.09.2024, 27.09.2024, 28.09.2024, 30.09.2024 в подвійному розмірі та 40% від посадового окладу (місячної тарифної ставки) за роботу в нічні години такому персоналу, а саме:
    </p>

        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
        ВЯБ:
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
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
</div>
<script>
    const Workings=@json($Workings);
    console.log(Workings);
</script>
@endsection