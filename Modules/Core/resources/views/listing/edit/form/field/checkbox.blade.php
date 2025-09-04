@php
    $valueKey = $field['id'] ?? $field['name'] ?? null;
    $dbValue  = $valueKey && $row ? ($row->$valueKey ?? null) : null;

    $isChecked = old($field['name'], $dbValue) 
                 ? true 
                 : (!empty($field['checked']) ? true : false);

    $inline = $field['inline'] ?? true;  // render inline by default
    $switch = $field['switch'] ?? false; // render as toggle switch
@endphp

<div class="mb-3">
    <div class="form-check 
        {{ $inline ? 'd-inline-flex align-items-center me-3' : '' }} 
        {{ $switch ? 'form-switch' : '' }}">
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
    </div>

    @error($field['name'])
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    @if(!empty($field['help']))
        <small class="form-text text-muted">{{ $field['help'] }}</small>
    @endif
</div>
