@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12 d-flex justify-content-between">
            <h1>{{ __('Stage') }}</h1>
            <a href="{{ route('stages.index') }}" class="btn btn-secondary">{{ __('Back to list') }}</a>
        </div>  
    </div>
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $stage->name }}</h2>
                </div>
                <div class="card-body">
                    <p>{!! nl2br(e($stage->description)) !!}</p>
                    <div class="form-group mb-2">
                        <label for="new_step">{{ __('New Step') }}</label>
                        <div id="successful_step"></div>
                        <input type="text" class="form-control" id="new_step" name="new_step" value="">
                        <button type="button" class="btn btn-primary mt-2" onclick="add_new_step({{ $stage->id }})">{{ __('Add') }}</button>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('stages.edit', $stage) }}" class="btn btn-warning">{{ __('Edit') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <h2>{{ __('Generate blank') }}</h2>
        </div>
        <div class="col-md-9">
            <h2>{{ __('Action positions') }}</h2>
                <select name="act_pos[]" id="act_pos" class="form-control" multiple >
                    @php $positions = App\Models\Position::all(); @endphp
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}">{{ $position->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-primary mt-2" onclick="generate_blank()">{{ __('Generate') }}</button>
        </div>
    </div>
    <form action="{{ route('stages.new_steps') }}" method="POST">
        @csrf
        @method('POST')
        <div id="form_steps">
            <div id="steps_container"></div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>

        const steps = @json($stage->steps);
        let steps_really = steps;
        const positions = @json(App\Models\Position::all());
        const form_steps = document.getElementById('steps_container');

        function order_steps(steps) {
            return steps.map((step, index) => ({ ...step, order: index + 1 }));
        }
        function change_order(index, value) {
            steps_really[index].order = parseInt(value);
            steps_really.sort((a, b) => a.order - b.order);
            show_tasks();
        }

        function show_tasks() {
            form_steps.innerHTML = '';
            steps_really.forEach((step, index) => {
                const div = document.createElement('div');
                div.classList.add('row', 'border', 'mb-2');
                div.innerHTML = `
                    <div class="col-md-2 d-flex align-items-center justify-content-between">
                        <button type="button" class="btn btn-light" onclick="move_step(${index}, 'up')">^</button>
                        <input type="number" name="steps[${index}][count_index]" class="form-control" value="${index + 1}"
                            style="width: 50px;" onchange="change_order(${index}, this.value)">
                        <input type="hidden" name="steps[${index}][order]" value="${index + 1}">
                        <button type="button" class="btn btn-light" onclick="move_step(${index}, 'down')">v</button>
                    </div>
                    <div class="col-md-2">
                        <p title="${step.description}">${step.name}</p>
                    </div>
                    <div class="col-md-2">
                        <select name="steps[${index}][position_id]" class="form-control">
                            ${positions.map(position => `<option value="${position.id}" ${position.id == step.position_id ? 'selected' : ''}>${position.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-1">
                        <input type="number" name="steps[${index}][count]" class="form-control" value="1"> {{__('things')}}
                    </div>
                    <div class="col-md-3">
                        {{__('Type')}}
                        <hr>
                        <input type="radio" name="steps[${index}][type]" value="doc" > {{__('doc')}}
                        <input type="radio" name="steps[${index}][type]" value="photo" checked> {{__('photo')}}
                        <input type="radio" name="steps[${index}][type]" value="sertificate"> {{__('sertificate')}}
                        <input type="radio" name="steps[${index}][type]" value="video"> {{__('video')}}    
                    </div>
                        
                    <div class="col-md-1">
                        <div class="form-check">
                            <input type="checkbox" name="steps[${index}][checkpoints]" class="form-check-input" checked>
                            <label class="form-check-label">{{__('inc')}}</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-primary mt-2" onclick="double_task(${index}, ${step.id})">{{ __('Double') }}</button>
                    </div>
                `;
                form_steps.appendChild(div);
            });
        }

        function move_step(index, direction) {
            if (direction === 'up' && index > 0) {
                [steps_really[index], steps_really[index - 1]] = [steps_really[index - 1], steps_really[index]];
            } else if (direction === 'down' && index < steps_really.length - 1) {
                [steps_really[index], steps_really[index + 1]] = [steps_really[index + 1], steps_really[index]];
            }
            steps_really = order_steps(steps_really);
            show_tasks();
        }
 

        function double_task(index, step_id) {
            // add new step after index
            const step = steps.find(step => step.id === step_id);
            steps_really.splice(index + 1, 0, { ...step, id: null });

            steps_really = order_steps(steps_really);
            show_tasks();
        }

        function generate_blank(){
            // выбрать все позиции для каждого действия и добавить в steps_really  количество действий должно быть равно количеству выбранных позиций * количество действий
            let act_pos = document.getElementById('act_pos');
            let act_pos_selected = [];
            for (let i = 0; i < act_pos.options.length; i++) {
                if (act_pos.options[i].selected) {
                    act_pos_selected.push(act_pos.options[i].value);
                }
            }
            let new_steps = [];
            for (let i = 0; i < act_pos_selected.length; i++) {
                for (let j = 0; j < steps_really.length; j++) {
                    new_steps.push({...steps_really[j], position_id: act_pos_selected[i]});
                }
            }
            steps_really = new_steps;
            
            show_tasks();


        }

        window.move_step = move_step;
        window.double_task = double_task;

        steps_really = order_steps(steps_really);
        show_tasks();
  
</script>
@endsection
