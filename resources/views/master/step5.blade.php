@extends('layouts.app')
@section('content')
    <div class="container">
               @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
        <div class="alert alert-success">{{ __(session('success')) }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ __(session('error')) }}</div>
    @endif
        <div class="row">
            <div class="col-md-12">
               
                <a class="text-right" class="btn btn-" href="{{ route('master.index') }}">{{__('Back')}}</a>
                
            
                

            <div class="row" >
                <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                
                                <div class="form-group">
                                    <form method="POST" action="{{ route('master_ending', $master->id) }}">
                                        @csrf
                                        <input type="hidden" name="done" id="done" value="1">
                                        <button type="submit" class="btn btn-success" onclick="document.getElementById('done').value='1';">{{ __('Complet') }}</button>
                                    </form>
                                    
                                </div>
                            </div>
                            <div class="col-md-6" >
                                
                                <div class="form-group">
                                    <form method="POST" action="{{ route('master_ending', $master->id) }}">
                                        @csrf
                                        <input type="hidden" name="done" id="done" value="0">
                                        <button type="submit" class="btn btn-warning" onclick="document.getElementById('done').value='0';">{{ __('Stop') }}</button>
                                    </form>
                                </div> 
                            </div>
                        </div>                   
  
                   
                </div>
            </div>
            <hr>
            <div class="row" >
                <div class="col-md-12">
                <h3>{{__('Task')}}: {{ $master->text }}</h3>
                @php $color = $master->urgency > 5 ? 'red' : ($master->urgency > 3 ? 'orange' : 'green') @endphp

                <h3>{{__('Urgency')}}: <span style="color: {{ $color }}"> {{ $master->deadline }}</span></h3>
                <h3>{{__('Basis')}}: {{ $master->basis }}</h3>
                <h3>{{__('Who give task')}}: {{ $master->who }}</h3>
                <h3>{{__('Comment')}}: {{ $master->comment }}</h3>
               
                    
            </div>
        </div>
    </div>    </div>
</div>
    <script>
  
    </script>
@endsection