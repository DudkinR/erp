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
    <div class="row" id="form_steps">
        <div class="col-md-12">
            <div class="row font-weight-bold">
                <div class="col-md-3">{{ __('Step name') }}</div>
                <div class="col-md-3">{{ __('Description') }}</div>
                <div class="col-md-3">{{ __('Position select') }}</div>
                <div class="col-md-3">{{ __('Controls') }}</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const steps = @json($stage->steps);
    const stage_id = @json($stage->id);
    const token = @json(csrf_token());
    const positions = @json(App\Models\Position::all());
    const form_steps = document.getElementById('form_steps');

    function show_tasks() {
        form_steps.innerHTML = '';
        steps.forEach((step, index) => {
            const div = document.createElement('div');
            div.classList.add('row', 'mb-2');
            div.innerHTML = `
            <div class="col-md-1">
            <button type="button" class="btn btn-light">^</button>
                ${index + 1}
                <button type="button" class="btn btn-light"> v</button>
            </div>
                <div class="col-md-3">
                    <p title="${step.description}"
                    >${step.name}</p>
                </div>
                <div class="col-md-3">
                    <select name="steps[${index}][position_id]" class="form-control">
                        ${positions.map(position => `<option value="${position.id}">${position.name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="count_index[${index}]" class="form-control mb-2" placeholder="{{ __('Count index') }}" value=" ${index + 1}">
                    <input type="number" name="count[${index}]" class="form-control mb-2" placeholder="{{ __('Count') }}" value="1">
                    <div class="form-check">
                        <input type="checkbox" name="checkpoints[${index}]" class="form-check-input" checked>
                        <label class="form-check-label">{{ __('Checkpoints') }}</label>
                    </div>
                    <button type="button" class="btn btn-primary mt-2" onclick="add_new_task(${index}, ${step.id})">{{ __('Double') }}</button>
                </div>
            `;
            form_steps.appendChild(div);
        });
    }

    show_tasks();
});
</script>
@endsection
