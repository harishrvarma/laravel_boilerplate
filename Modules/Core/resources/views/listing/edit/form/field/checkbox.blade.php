@php
    $valueKey = $field['id'] ?? $field['name'] ?? null;
    $dbValue  = $valueKey && $row ? ($row->$valueKey ?? null) : null;

    $isChecked = old($field['name'], $dbValue) 
                 ? true 
                 : (!empty($field['checked']) ? true : false);
@endphp

<div class="form-check mb-3">
    <input 
        type="checkbox" 
        id="{{ $field['id'] ?? $field['name'] }}" 
        name="{{ $field['name'] }}" 
        value="{{ $field['value'] ?? 1 }}" 
        class="form-check-input {{ $field['class'] ?? '' }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
        @if($isChecked) checked @endif
        @if(!empty($field['required'])) required @endif
    >
    <label class="form-check-label" for="{{ $field['id'] ?? $field['name'] }}">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>
    @error($field['name']) <div class="invalid-feedback">{{ $message ?? '' }}</div> @enderror
</div>
