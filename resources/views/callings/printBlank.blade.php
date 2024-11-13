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

    <p class="MsoNormal"><b>БЛАНК від <u>
        <span style="color: {{ empty($calling->start_time) ? 'yellow' : 'black' }}">
        {{ empty($calling->start_time) ? 'ХХХХХХХ' : date('d.m.Y', strtotime($calling->start_time)) }}
    </span>
        № {{$calling->id}}</u></b></p>

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

    <p class="MsoNormal" style="margin-top:6.0pt">Час виклику:<u>
    <span style="color: {{ empty($calling->start_time) ? 'yellow' : 'black' }}">
        {{ empty($calling->start_time) ? 'ХХХХХХХ' : date('d.m.Y H:i', strtotime($calling->start_time)) }}
    </span>
    
    </u> година <u>
    <span style="color: {{ empty($calling->start_time) ? 'yellow' : 'black' }}">
        {{ empty($calling->start_time) ? 'ХХХХХХХ' : date('d.m.Y H:i', strtotime($calling->start_time)) }}
    </span>
    </u>хв. <b> <u>
    <span style="color: {{ empty( $calling->personal_arrival_id) ? 'yellow' : 'black' }}">
        {{ empty( $calling->personal_arrival_id) ? 'ХХХХХХХ' : \App\Models\Personal::where('tn', $calling->personal_arrival_id )->first()->fio }}
    </span>
    </u></b></p>

    <p class="MsoNormal" >Час прибуття бригади <u>
    <span style="color: {{ empty($calling->start_time) ? 'yellow' : 'black' }}">
        {{ empty($calling->start_time) ? 'ХХХХХХХ' : date('d.m.Y H:i', strtotime($calling->start_time)) }}
    </span>
    </u> година <u>
    <span style="color: {{ empty($calling->start_time) ? 'yellow' : 'black' }}">
        {{ empty($calling->start_time) ? 'ХХХХХХХ' : date('d.m.Y H:i', strtotime($calling->start_time)) }}
    </u> хв. у складі <u>
    <span style="color: {{ empty($calling->workers->count()) ? 'yellow' : 'black' }}">
        {{ empty($calling->workers->count()) ? 'ХХХХХХХ' : $calling->workers->count() }}
    </span>
    </u> осіб підтверджую:</p>

    <p class="MsoNormal">НЗ АЕС (НЗЦ, НЗ КГ) <b> <u>
    <span style="color: {{ empty($calling->personal_start_id) ? 'yellow' : 'black' }}">
        {{ empty($calling->personal_start_id) ? 'ХХХХХХХ' : \App\Models\Personal::where('tn', $calling->personal_start_id )->first()->fio }}
    </span>
    </u></b></p>

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
            <td width="126">оплаті</td>
            <td width="126">наданню іншого дня відпочинку</td>
        </tr>
        @php   
            $Kerivnyk_bryhady = App\Models\Type::where('slug', 'Kerivnyk-bryhady')->first();
            $Kerivnyk='';
        @endphp
                         
        @foreach($calling->workers as $worker)
        @php 
            if($worker->positions[0]['type_id']==$Kerivnyk_bryhady->id){
                $Kerivnyk=$worker;
            }
        @endphp
        <tr>
            <td>{{ $worker->fio }}</td>
            <td>{{ $worker->tab_number }}</td>
            <td>{{ $worker->positions[0]['name'] ?? '—' }}</td>
            <td colspan="3">
                @if($worker->pivot->start_time)
                    {{ date('d.m.Y H:i', strtotime($worker->pivot->start_time)) }} -
                    {{ date('d.m.Y H:i', strtotime($worker->pivot->end_time)) }}
                @else
                    <span style="color: yellow;">ХХХХХХХ</span>
                @endif
            </td>
            <td>
                &nbsp;
            </td>
        </tr>
        @endforeach
    </table>
    <p>Керівник бригади
        <b> <u>
        <span style="color: {{ empty($Kerivnyk) ? 'yellow' : 'black' }}">
            {{ empty($Kerivnyk) ? 'ХХХХХХХ' : $Kerivnyk->fio }}
        </span>
        </u></b> _______________________    _________
    </p>
    <p style="margin-top:-14pt;">
          підпис ПІБ дата
    </p>
    <p>Виклик бригади  даного складу  для <u>
        <span style="color: {{ empty($calling->arrival_time) ? 'yellow' : 'black' }}">
            {{ empty($calling->arrival_time) ? 'ХХХХХХХ' : $calling->arrival_time }}
        </span>
    </u> підтверджую:</p>
    <p>НЗ АЕС (НЗЦ, НЗ КГ) __________________   ____________________________</p>    
    <p style="margin-top:-14pt;">(необхідне підкреслити)                     підпис                                          ПІБ</p>

    <p>Роботи з усунення несправності (вантажно-розвантажувальні роботи) закінчені, бригада відправлена додому в <u>
        <span style="color: {{ empty($calling->end_time) ? 'yellow' : 'black' }}">
            {{ empty($calling->end_time) ? 'ХХХХХХХ' : date('H:i', strtotime($calling->end_time)) }}
        </span>
    </u> годину <u>
        <span style="color: {{ empty($calling->end_time) ? 'yellow' : 'black' }}">
            {{ empty($calling->end_time) ? 'ХХХХХХХ' : date('H:i', strtotime($calling->end_time)) }}
        </span>
    </u>хв. дата<u>
        <span style="color: {{ empty($calling->end_time) ? 'yellow' : 'black' }}">
            {{ empty($calling->end_time) ? 'ХХХХХХХ' : date('d.m.Y', strtotime($calling->end_time)) }}
        </span>
    </u></p>
    <p>НЗ АЕС (НЗЦ, НЗ КГ)  ____________        ______________</p>
    <p style="margin-top:-14pt;">(необхідне підкреслити)               підпис                          ПІБ</p>
    <p>Начальник підрозділу:
        
        _____________         ______________</p>
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