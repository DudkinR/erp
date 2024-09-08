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
                <h1>{{__('Master')}}</h1>
                <form method="POST" action="{{ route('master.store') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                      <div class="form-group" style="margin-top: 20px;">
                        <label for="task">{{__('Task')}}
                             <small><b>
                                ({{__('Text of task')}})
                                </b></small>
                             </label>
                        <textarea class="form-control" id="task" name="task">{{ old('task') }}</textarea>
                      </div>
                    <div class="form-group" id="preview_task">
                        
                    </div> 
                    <div class="form-group">
                        <label for="urgency">{{__('Urgency')}}
                            <small><b>
                                ({{__('1 - not urgent, 10 - very urgent')}})
                                </b></small>
                        </label>
                        <input type="number" class="form-control" id="urgency" name="urgency" value="{{ old('urgency', 1) }}" min="1" max="10">
                    </div>
                    <div class="form-group">
                        <label for="deadline">{{ __('Deadline') }}
                            <small><b>
                                ({{__('Date and time of task completion')}})
                                </b></small>
                        </label>
                        <input type="datetime-local" class="form-control" id="deadline" name="deadline" value="{{ old('deadline', now()->addHours(8)->format('Y-m-d\TH:i')) }}">
                    </div>

                    <div class="form-group">
                        <label for="basis">{{__('Basis')}}
                            <small><b>
                                ({{__('The basis for the assignment of the task')}})
                                </b></small>
                        </label>

                        <textarea class="form-control" id="basis" name="basis"  >{{ old('basis') }}</textarea>
                        <br>
                        <select class="form-control" id="basis_add">
                            <option value="" selected>{{__('Select basis')}}</option>
                            <option value="{{__('Hand Order')}} №" >{{__('Hand Order')}}</option>
                            <option value="{{__('Order')}} №" >{{__('Order')}}</option>
                            <option value="{{__('Reglament')}} №" >{{__('Reglament')}}</option>
                            <option value="{{__('Instruction')}} №" >{{__('Instruction')}}</option>
                            <option value="{{__('Manual')}} №" >{{__('Manual')}}</option>
                            <option value="{{__('Scadule')}} №" >{{__('Scadule')}}</option>
                            <option value="{{__('Plan')}} №" >{{__('Plan')}}</option>
                            <option value="{{__('Task')}} №" >{{__('Task')}}</option>
                            <option value="{{__('Letter')}} №" >{{__('Letter')}}</option>
                            <option value="{{__('Email')}} №" >{{__('Email')}}</option>
                            <option value="{{__('Phone call')}} №" >{{__('Phone call')}}</option>
                            <option value="{{__('Conversation')}} №" >{{__('Conversation')}}</option>
                           
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Who">{{__('Who give task')}}
                            <small><b>
                                ({{__('Position of the person who gives the task or his name')}})
                                </b></small>
                        </label>
                        <input type="text" class="form-control" id="who" name="who" value="{{ old('who') }}">
                    </div>   
                    <div class="form-group">
                        <label for="Comment">{{__('Comment')}}
                            <small><b>
                                ({{__('Additional information')}})
                                </b></small>
                        </label>
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
        const task = document.getElementById('task');
        const preview_task = document.getElementById('preview_task');
        task.addEventListener('keyup', (e) => {
    
           if(task.value.length > 0) {
            fetch('/search-text-task', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ task: task.value })
                })
                .then(response => response.json())
                .then(data => {
                    preview_task_show(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
              } else {
                preview_task.innerHTML = '';
                
           }
        }); 
        function previews_to_tasks(text) {
            task.value = text;
            
        }
        function preview_task_show(data) {
            let preview_task_html = '';
            data.forEach(item => {
                preview_task_html += `
                    <div class="card" style="margin-top: 10px;">
                            <h5 class="card-title" onclick="previews_to_tasks('${item}')">${item}</h5>
                    </div>
                `;
            });
            preview_task.innerHTML = preview_task_html;
        }

    </script>
@endsection