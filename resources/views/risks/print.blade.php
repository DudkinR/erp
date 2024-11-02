@extends('layouts.print')
@section('content')
<!-- very siple page for print -->
@php

@endphp
<style>
    td{
        font-size:20px;
    }
    .risk{
    
         position:fixed;
         z-index:1;
         top:0px;
         right:0px;
         overflow-x:hidden;
         padding-top:8px 0;
     }
</style>
<div class="risk row shadow p-1 mb-2">
    <div class="col" id="pr2">
        <h4 id="price2"></h4>
        <h4>{{ __('Print') }}</h4>
    </div>
</div>
<form name="add_brief" id="add_brief" method="POST" action="str/add_brief.php">
    <!-- Hidden inputs for data -->
    @foreach (['tn_NSB' => '{{$tn}}', 'n_NSB' => '{{$fio}}', 'unit' => '', 'n_act' => '{{$full_name}}', 'd_action' => '{{$date}}', 'tnr_NSB' => '', 'Action' => '312,', 'equipment' => 'Array', 'risk' => '5.66', 'risk_dop' => '0', 'events' => '1,11,12,13,...'] as $name => $value)
        <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}" />
    @endforeach
</form>
<div class="container">
    <h3 class="text-center">{{ __('Briefing form') }} №____.{{$tn}}</h3>
    <div class="row">
        <div class="col-3"><h4>{{ __('Operation name') }}: </h4></div>
        <div class="col-9"><h4><b>{{ $full_name }}</b></h4></div>
    </div>
    <h3>{{ __('Date of operation execution') }}: <b><u>{{ $date }}</u></b></h3>
    <h3 class="text-center">{{ __('Preparatory activities') }}.</h3>

    <!-- Briefing Actions Section -->
    <div class="row border">
        <div class="col-11">
            <h3><strong>{{ __('Action') }}</strong></h3>
        </div>
        <div class="col-1 border text-center">
            <input type="button" id="select_all" class="btn btn-secondary btn-sm" value="Select All" />
        </div>
    </div>
    <input type="hidden" name="risk" value="{{$risk}}">
    <input type="hidden" name="num_ch" value="0">
    <!-- Briefing Items Loop -->
    @php $i = 0; @endphp
    @foreach($briefs->where('functional', '1') as $brief)
        <div class="row border">
            <div class="col-11">
                {!! $brief->name_uk ?: $brief->name_en ?: $brief->name_ru !!}
            </div>
            <div class="col-1 border text-center">
                <input type="checkbox" class="control-input" name="actions_{{ $i }}" id="action_{{ $i }}" value="{{ $i }}">
            </div>
        </div>
        @php $i++; @endphp
    @endforeach   
</div>
<div class="container" >
    <h3 align="center">{{__('Briefing before starting work')}}.</h3>
    <div class="row border">
            <div class="col-11">
            <h3><strong>{{__('Action')}}</strong></h3>
        </div>
        <div class="col-1 border">
            <strong><SMALL>.</SMALL></strong>

        </div>
    </div>
    @foreach($briefs->where('functional', '2') as $brief)
        <div class="row border">
            <div class="col-11">
                {!! $brief->name_uk ?: $brief->name_en ?: $brief->name_ru !!}
            </div>
            <div class="col-1 border">
                <input type="checkbox" class="control-input" name="actions_{{$i}}" id="{{$i}}" value="{{$i}}" >
            </div>
        </div>
    @endforeach
</div>
<div class="container" >
<strong>{{__('Experiences of operation')}}:</strong>
@foreach($experiences as $experience)
    <div class="row border">
        <div class="col-11">
            {!! $experience->text_uk ?: $experience->text_en ?: $experience->text_ru !!}
        </div>
        <div class="col-1 border">
            <input type="checkbox" class="control-input" name="actions_{{$i}}" id="{{$i}}" value="{{$i}}" >
        </div>
    </div>
@endforeach
</div>
<div class="container" >
<table class="container" border="0" cellpadding="0" cellspacing="0" >
<tr><td COLSPAN=2><strong>{{__('Briefing on this form in the specified sequence in accordance with the operational and technical documentation has been completed')}}.</strong></td></tr>
<tr><td COLSPAN=2><strong>{{__('Switching is permitted, the controller has been briefed, the performers')}}:</strong></td></tr>
</table>
<table class="container" border="0" cellpadding="0" cellspacing="0" >
<tr><td><strong>{{__('Switching Manager')}}: </strong></td><td><i>{{$fio}}</i>___________________________</td></tr>
<tr><td>&nbsp; </td><td class="text-center"><sup><i>({{__('Signature')}})</i> </sup></td></tr>
</table>

<h4>{{__('Briefing after completion of work')}}.</h4>
@foreach($briefs->where('functional', '3') as $brief)
    <div class="row border">
        <div class="col-11">
            {!! $brief->name_uk ?: $brief->name_en ?: $brief->name_ru !!}
        </div>
        <div class="col-1 border">
            <input type="checkbox" class="control-input" name="actions_{{$i}}" id="{{$i}}" value="{{$i}}" >
        </div>
    </div>
@endforeach
</div>
<div class="container" >
    <div class="row border">
        <div class="col-11">
        <h3>{{__('The briefing is over')}}.</h3>
        </div>
        <div class="col-1 border">
        <input type="checkbox"  name="fin_brif" id="19" value="" >
        </div>

    </div>
</div>
<script language="JavaScript" type="text/javascript">
	/*<![CDATA[*/
	function add_remarks(id) {
	window.open("?id="+id,  "dell", "width=800, height=500, toolbar=no", "status=no");
	}
	/*]]>*/
</script>
<div class="container" >
    <div class="row ">
        <div class="col-12">
            <a href="#" class="btn btn-block btn-light" onClick="add_remarks(687);" title="{{__('Submit comments')}}">{{__('Notes')}}</a>
            <table class="container" border="0" cellpadding="0" cellspacing="0" >
                <tr>
                    <td>
                        <strong>{{__('Switching Manager')}}: </strong>
                    </td>
                    <td>
                        <i>{{$fio}}</i>
                    ___________________________</td></tr>
                <tr>
                    <td>&nbsp; </td>
                    <td class="text-center">
                        <sup><i>({{__('Signature')}})</i> </sup>
                    </td>
                </tr>
                </table>
        </div>
    </div>
</div>

<input type="hidden" id="res" value="0" />
<input type="hidden" id="start" value="5.66" />
<input type="hidden" id="izm" value="0" />
<input type="hidden" id="ch_last_cl" value="1" />
<div class="" id="content_brif"></div>
<script type="text/javascript">
 


</script>

@endsection