@php
    $value = $field['id'] ?? '';
    $value = $row->$value;
@endphp
<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>
    <select 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        class="form-select {{ $field['class'] }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
        {{ $field['required'] }}
        {{ $field['multiselect'] ?? ''}}
    >
        <option value="">-- {{ $field['placeholder'] ?? 'Select' }} --</option>
        @foreach(($field['options']) as $key => $option)
            <option value="{{ $key }}" 
                {{ old($field['name'],$value) == $key ? 'selected' : '' }}>
                {{ $option }}
            </option>
        @endforeach
    </select>
    @error($field['name']) <div class="invalid-feedback">{{ $message ?? '' }}</div> @enderror
</div>
