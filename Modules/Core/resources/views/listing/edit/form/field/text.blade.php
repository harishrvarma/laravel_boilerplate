@php
    $key = $field['id'] ?? '';
    $value = $row->$key ?? $field['value'] ?? '';

    // If it's an array (e.g., multi-select), convert to comma-separated string
    if (is_array($value)) {
        $value = implode(',', $value);
    }
@endphp

<div class="form-group">
    <label for="{{ $field['id'] }}" class="form-label">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>

    <input 
        type="text" 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        value="{{ old($field['name'], $value) }}"
        class="form-control {{ $field['class'] ?? '' }} @error($field['name']) is-invalid @enderror"
        placeholder="{{ $field['placeholder'] ?? '' }}"
        {{ !empty($field['required']) ? 'required' : '' }}
    >

    @if (!empty($field['note']))
        <small class="form-text text-muted">{{ $field['note'] }}</small>
    @endif

    @error($field['name'])
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
