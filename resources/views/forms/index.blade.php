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
            <h1>{{__('Procedures')}}</h1>
                <a class="btn btn-light w-100" href="{{ route('forms.create') }}">{{__('Create')}}</a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <h1>{{__('Division')}}</h1>
            </div>
            <div class="col-md-9">
                <div class="form-select">
                    <select id="division" name="division" class="form-control">
                        @foreach($divisions as $division)
                            <option value="{{ $division->id }}" style="font-weight: bold;">{{ $division->name }}</option>
                            @php $children = $division->children; $prefix=$division->name."  "; @endphp
                            @foreach($children as $child)
                                <option value="{{ $child->id }}">{{ $prefix . $child->name }}</option>
                                @if($child->children->count() > 0)
                                    @include('partials.division-options', ['children' => $child->children, 'prefix' => $prefix . '- '])
                                @endif
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row" id="show_forms">

        </div>

    </div>
    <script>
        const divisions = @json($divisions);
        const forms = @json($forms);
        
        console.log(forms); // Check the forms in console
        const show_forms = document.getElementById('show_forms');
        
        // Handle division selection
        $('#division').on('change', function() {
    const selectedDivision = parseInt($(this).val());
    console.log('Selected Division:', selectedDivision);
    let forms_html = '';

    // Iterate over forms and check if the selected division is present
    forms.forEach(form => {
        console.log('Form Divisions:', form.divisions);

        // Only show forms associated with the selected division
        if (form.divisions.length > 0 && form.divisions.includes(selectedDivision)) {
            forms_html += `
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${form.name}</h5>
                            <p class="card-text">${form.description}</p>
                            <a href="{{ url('forms') }}/${form.id}" class="btn btn-primary">{{__('View')}}</a>
                        </div>
                    </div>
                </div>
            `;
        }
    });

    if (forms_html === '') {
        forms_html = '<p>No forms available for this division.</p>';
    }

    $('#show_forms').html(forms_html);
});
    </script>
@endsection