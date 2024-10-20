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
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{__('Calling form')}}  
                <span  style="font-size: 70%; border-bottom: 1px solid #000; padding-top: 5px;"
                > {{__('from')}} {{$calling->created_at->format('d.m.Y')}}</span>
                 № {{$calling->id}}</h1>
            <h2>{{__('Type')}}: <u> {{$ParentType->name}}</u></h2>
            <h3>{{__('Start time')}}: 
                <u>
                    @if($calling->start_time)
                         {{ \Carbon\Carbon::parse($calling->start_time)->format('H година i хв. d.m.Y') }}
                    @else
                        {{ __('Not specified') }}
                    @endif
                </u>
            </h3>
            <h4 title="{{$CallingType->name}}">
                {{$CallingType->description}}
            </h4>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>{{__('Description')}}</h3>
            <p>{{$calling->description}}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>{{__('Team`s complition')}}</h3>
            <table class="table table-bordered">                
                <thead>
                    <tr>
                        <th>{{__('FIO')}}</th>
                        <th>{{__('tab.#')}}</th>
                        <th>{{__('Position')}}</th>
                        <th>{{__('Time calling is')}}</th>
                        <th>{{__('Signature')}}</th>
                    </tr>
                <tbody>
                    @foreach($calling->workers as $worker)
                    <tr>
                        <td>{{$worker->fio}}</td>
                        <td>{{$worker->tn}}</td>
                        <td>{{$worker->positions[0]->name }}</td>
                        <td>
                            {{$Oplata_pratsi_ids->where('id', $worker->pivot->payment_type_id)->first()->name}}
                        </td>
                        <td></td>

                   
                    </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <p>{!!__('text zgoda')!!}</p>
        </div>
    </div>
    <div class="row" id="button_area">
        <div class="col-md-12">
            <button class="btn btn-light w-100" onclick="print()">{{__('Print')}}</button>
        </div>
    </div>
</div>
<script>
    function print() {
        document.getElementById('button_area').style.display = 'none';
        window.print();
        // close window after print
        setTimeout(function() {
            window.close();
        }, 100);

    }
</script>

@endsection