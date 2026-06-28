@props([
    'options' => [],
    'selected' => null,
])

<select name="process_type" id="process_type"
    class="form-select @error('process_type') is-invalid @enderror" required>

    <option value="" disabled {{ !$selected && !old('process_type') ? 'selected' : '' }}>
        Оберіть тип процесу...
    </option>

    @foreach($options as $value => $label)
        <option value="{{ $value }}"
            {{ (string) old('process_type', $selected) === (string) $value ? 'selected' : '' }}>
            {{ $label }}
        </option>
    @endforeach

</select>