@php
    $value = $field['id'] ?? '';
    $value = $row->$value;
@endphp
<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>
    <input 
        type="password" 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        class="form-control {{ $field['class'] }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
        placeholder="{{ $field['placeholder'] }}"
        {{ $field['required'] }}
        value="{{ old($field['name'],  $value) }}"
    >
    @error($field['name']) <div class="invalid-feedback">{{ $message ?? '' }}</div> @enderror
</div>
