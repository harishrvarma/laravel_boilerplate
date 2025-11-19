@php
$valueKey = $field['id'] ?? $field['name'];

$val = $row->$valueKey ?? $field['value'] ?? '';

$currentValue = old($field['name'], is_numeric($val) ? $val : '');
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
        @if(isset($field['min'])) min="{{ $field['min'] }}" @endif
        @if(isset($field['max'])) max="{{ $field['max'] }}" @endif
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

