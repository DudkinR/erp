<!-- resources/views/components/select.blade.php -->
<div class="mb-3">
    <label for="{{ $id }}" class="form-label fw-semibold">{{ $label }}</label>
    <input type="text" id="search_{{ $id }}" class="form-control mb-2" placeholder="Пошук...">
    <select name="{{ $name }}[]" id="{{ $id }}" class="form-select" multiple style="min-height: {{ $height ?? '100px' }};">
        @foreach($options as $option)
            <option value="{{ $option['id'] }}" {{ in_array($option['id'], old($name, [])) ? 'selected' : '' }}>
                {{ $option['text'] }}
            </option>
        @endforeach
    </select>
</div>
