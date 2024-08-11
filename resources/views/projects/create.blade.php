@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Project')}}</h1>
                <form method="POST" action="{{ route('projects.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group mb-2">
                        <label for="name">{{__('Name')}}</label>
                        <input type="text" class="form-control" id="name" name="name" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="description">{{__('Description')}}</label>
                        <textarea class="form-control" id="description" name="description" rows="5" ></textarea>
                        
                    </div>
                    <div class="form-group mb-2">
                        <label for="priority">{{__('Priority')}}</label>
                        <input type="number" class="form-control" id="priority" name="priority" value="0" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="number">{{__('Number')}}</label>
                        <input type="text" class="form-control" id="number" name="number" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="date">{{__('Date')}}</label>
                        <input type="date" class="form-control" id="date" name="date" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="amount">{{__('Amount')}}</label>
                        <input type="number" class="form-control" id="amount" name="amount" >
                    </div>
                    <div class="form-group  mb-2">
                        <label for="client">{{__('Client')}}</label>
                        <?php $clients = App\Models\Client::orderBy('id', 'desc')->get(); ?>
                        <select name="client" id="client" class="form-control">
                            <option value="0">{{__('New client')}}</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <hr>
                        <label for="client">{{__('New Client')}}</label>
                        <input type="text" class="form-control" id="new_client" name="new_client" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="current_state">{{__('Current State')}}</label>
                        <select name="current_state" id="current_state" class="form-control">
                            <option value="Очікується погодження">{{__('Очікується погодження')}}</option>
                            <option value="Готовий до забезпечення">{{__('Готовий до забезпечення')}}</option>
                            <option value="Готовий до відвантаження">{{__('Готовий до відвантаження')}}</option>
                            <option value="У процесі відвантаження">{{__('У процесі відвантаження')}}</option>
                            <option value="Очікується оплата (після відвантаження)">{{__('Очікується оплата (після відвантаження)')}}</option>
                            <option value="Готовий до закриття">{{__('Готовий до закриття')}}</option>
                             <option value="Закритий">{{__('Закритий')}}</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="execution_period">{{__('Execution Period')}}</label>
                        <input type="date" class="form-control" id="execution_period" name="execution_period" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="payment_percentage">{{__('Payment Percentage')}}</label>
                        <input type="number" class="form-control" id="payment_percentage" name="payment_percentage" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="shipping_percentage">{{__('Shipping Percentage')}}</label>
                        <input type="number" class="form-control" id="shipping_percentage" name="shipping_percentage" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="debt_percentage">{{__('Debt Percentage')}}</label>
                        <input type="number" class="form-control" id="debt_percentage" name="debt_percentage" >
                    </div>
                    <div class="form-group mb-2">
                        <label for="currency">{{__('Currency')}}</label>
                        <select name="currency" id="currency" class="form-control">
                            <option value="грн" selected >{{__('UAH')}}</option>
                            <option value="$">{{__('USD')}}</option>
                            <option value="€">{{__('EUR')}}</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="operation">{{__('Operation')}}</label>
                        <input type="text" class="form-control" id="operation" name="operation" value="Реалізація" >
                    </div>
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection