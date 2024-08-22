@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Master')}}</h1>
                <form method="POST" action="{{ route('master.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group" style="margin-top: 20px;">
                        <label for="task">{{__('Task')}}</label>
                        <textarea class="form-control" id="task" name="task">{{ old('task') }}</textarea>
                      </div>
                    <div class="form-group">
                        <label for="urgency">{{__('Urgency')}}</label>
                        <input type="number" class="form-control" id="urgency" name="urgency" value="{{ old('urgency', 1) }}" min="1" max="10">
                    </div>
                    <div class="form-group">
                        <label for="deadline">{{ __('Deadline') }}</label>
                        <input type="datetime-local" class="form-control" id="deadline" name="deadline" value="{{ old('deadline', now()->addHours(8)->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="form-group">
                        <label for="basis">{{__('Basis')}}</label>

                        <textarea class="form-control" id="basis" name="basis"  >{{ old('basis') }}</textarea>
                        <br>
                        <select class="form-control" id="basis_add">
                            <option value="" selected>{{__('Select')}}</option>
                            <option value="{{__('Hand Order')}} №" >{{__('Hand Order')}}</option>
                            <option value="{{__('Order')}} №" >{{__('Order')}}</option>
                            <option value="{{__('Reglament')}} №" >{{__('Reglament')}}</option>
                            <option value="{{__('Instruction')}} №" >{{__('Instruction')}}</option>
                            <option value="{{__('Manual')}} №" >{{__('Manual')}}</option>
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Who">{{__('Who give task')}}</label>
                        <input type="text" class="form-control" id="who" name="who" value="{{ old('who') }}">
                    </div>   
                    <div class="form-group">
                        <label for="Comment">{{__('Comment')}}</label>
                        <textarea class="form-control" id="comment" name="comment">{{ old('comment') }}</textarea>
                        

                    </div>
                    <button type="submit" class="btn btn-primary">{{__('Create')}}</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('basis_add').addEventListener('change', function() {
            var basis = document.getElementById('basis');
            basis.value = basis.value + (basis.value ? "\n" : "") + this.value;
            this.value = '';
            basis.focus();
        });
    </script>
@endsection