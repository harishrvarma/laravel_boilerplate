@php
    $value = $field['id'] ?? '';
    $value = $row->$value;
@endphp
<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>
    <textarea 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        class="form-control {{ $field['class'] }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
        rows="{{ $field['rows'] ?? 4 }}"
        placeholder="{{ $field['placeholder'] ?? '' }}"
        {{ !empty($field['required']) ? 'required' : '' }}
    >{{ old($field['name'], $value ?? ($field['value'] ?? '')) }}</textarea>
    @error($field['name']) <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
