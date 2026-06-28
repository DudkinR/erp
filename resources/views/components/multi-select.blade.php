@props([
    'options' => [],
    'name',
    'id',
    'selected' => [],
    'required' => false,
    'height' => '150px'
])

@php
    $values = old($name, $selected ?? []);
@endphp

<select name="{{ $name }}[]" id="{{ $id }}"
    class="form-select @error($name) is-invalid @enderror"
    multiple {{ $required ? 'required' : '' }}
    style="min-height: {{ $height }};">

    @foreach($options as $option)
        <option value="{{ $option['id'] }}"
            {{ in_array($option['id'], $values) ? 'selected' : '' }}>
            {{ $option['text'] }}
        </option>
    @endforeach

</select>

@error($name)
    <div class="invalid-feedback">{{ $message }}</div>
@enderror