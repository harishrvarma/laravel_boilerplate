@php
    $valueKey = $field['id'] ?? $field['name'];
    $currentValue = old($field['name'], $row->$valueKey ?? '');
@endphp

<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>

    <input 
        type="number" 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        value="{{ $currentValue }}"
        step="{{ $field['step'] ?? 'any' }}"
        min="{{ $field['min'] ?? '' }}"
        max="{{ $field['max'] ?? '' }}"
        class="form-control {{ $field['class'] ?? '' }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
        placeholder="{{ $field['placeholder'] ?? '' }}"
        @if(!empty($field['required'])) required @endif
    >

    @error($field['name'])
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    @if(!empty($field['help']))
        <small class="form-text text-muted">{{ $field['help'] }}</small>
    @endif
</div>
