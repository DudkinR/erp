@extends('layouts.print')
@section('content')
<!-- very siple page for print -->
@php
$CallingType = null;
$ParentType = null;

if ($calling->type_id != null) {
    $CallingType = \App\Models\Type::find($calling->type_id);
    $ParentType = $CallingType ? \App\Models\Type::find($CallingType->parent_id) : null;
} else {
    $CallingType = \App\Models\Type::where('slug', 'Oplata-pratsi')->first();
    $ParentType = $CallingType ? \App\Models\Type::find($CallingType->parent_id) : null;
}
@endphp
<div class="container" lang="UK" style="tab-interval:35.4pt">

<div class="WordSection1">

    <p class="MsoNormal"><b>БЛАНК від <u>{{ date('d.m.Y', strtotime($calling->start_time)) }} № {{$calling->id}}</u></b></p>

    <p class="MsoNormal">
        @if($ParentType->name == 'Надурочні роботи')
       <u> виклику на роботу в надурочний час, </u>
        неробочі, вихідні і святкові дні
        @else
        виклику на роботу в надурочний час, 
       <u>  неробочі, вихідні і святкові дні</u>
        @endif
    </p>

    <p class="MsoNormal" style="font-size:9.0pt; margin-top:-14pt;">(необхідне підкреслити)</p>

    <p class="MsoNormal" style="margin-top:6.0pt">Час виклику:<u>{{ date('d.m.Y H', strtotime($calling->arrival_time)) }}</u> година <u>{{ date('i', strtotime($calling->arrival_time)) }}</u>хв. <b> <u>{{ \App\Models\Personal::where('tn', $calling->personal_arrival_id )->first()->fio }}</u></b></p>

    <p class="MsoNormal" >Час прибуття бригади <u>{{ date('H', strtotime($calling->start_time)) }} </u> година <u>{{ date('i', strtotime($calling->start_time)) }}</u> хв. у складі <u>{{$calling->workers->count()}}</u> осіб підтверджую:</p>

    <p class="MsoNormal">НЗ АЕС (НЗЦ, НЗ КГ) <b> <u>{{ \App\Models\Personal::where('tn', $calling->personal_start_id )->first()->fio }}</u></b></p>

    <p class="MsoNormal" style="font-size:9.0pt; margin-top:-14pt;">(необхідне підкреслити) підпис ПІБ</p>

    <p class="MsoNormal" style="margin-bottom:6.0pt;">Склад бригади:</p>

    <table class="MsoNormalTable" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
    <tr>
    <td width="101" rowspan="2" valign="top">П.І.Б.</td>
    <td width="60" rowspan="2" valign="top">таб. №</td>
    <td width="144" rowspan="2" valign="top">посада, професія</td>
    <td width="252" colspan="3" valign="top">Час роботи за викликом підлягає</td>
    <td width="96" rowspan="2" valign="top">Підпис працівника</td>
    </tr>
    <tr>
    <td width="126" >оплаті	</td>
     <td width="126" >наданню іншого дня відпочинку наданню</td>
    </tr>
    </table>

    Керівник бригади___________________  _______________________    _________ <br>
                                            підпис                                      ПІБ                               дата<br>
Виклик бригади даного складу  для ___________________________________________________<br>
__________________________________________________________________________________<br>
(найменування роботи)<br>
підтверджую:<br>
НЗ АЕС (НЗЦ, НЗ КГ) __________________   ____________________________<br>
    (необхідне підкреслити)                     підпис                                          ПІБ<br>
    <br>
Роботи з усунення несправності (вантажно-розвантажувальні роботи) закінчені, бригада відправлена додому в ______ годину ______хв. дата_____________<br>
НЗ АЕС (НЗЦ, НЗ КГ)  ____________        ______________<br>
    (необхідне підкреслити)               підпис                          ПІБ<br>

Начальник підрозділу: _____________         ______________<br>
                                                підпис                               ПІБ<br>

Взяття на облік виклику:<br>

Начальник СВНіПБ _____________ ______________________ _____________<br>
                                              підпис                         ПІБ                               дата<br>

Надано дозвіл:<br>

Голова профкому     _________________<br>


</div>

    <div class="row" id="button_area">
        <div class="col-md-12">
            <button class="btn btn-light w-100" onclick="printPage()">{{__('Print')}}</button>
        </div>
    </div>
</div>
<script>
    function printPage() {
        document.getElementById('button_area').style.display = 'none';
        window.print();
        // close window after print
      
    }

</script>

@endsection