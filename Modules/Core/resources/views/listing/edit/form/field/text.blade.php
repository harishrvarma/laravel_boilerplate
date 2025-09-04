@php
    $value = $field['id'] ?? '';
    $value = $row->$value ?? null;
@endphp

<div class="form-group">
    <label for="{{ $field['id'] }}" class="form-label">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>

    <input 
        type="text" 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        value="{{ old($field['name'], $value ?? $field['value'] ?? '') }}"
        class="form-control {{ $field['class'] ?? '' }} @error($field['name']) is-invalid @enderror"
        placeholder="{{ $field['placeholder'] ?? '' }}"
        {{ !empty($field['required']) ? 'required' : '' }}
    >

    @error($field['name'])
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
