@php
    $value = $field['id'] ?? '';
    $value = $row->$value;
@endphp
<div class="mb-3">
    <label class="form-label d-block">{{ $field['label'] ?? ucfirst($field['name']) }}</label>
    @foreach(($field['options'] ?? []) as $key => $option)
        <div class="form-check form-check-inline">
            <input 
                type="radio" 
                id="{{ $field['id'].'_'.$key }}" 
                name="{{ $field['name'] }}" 
                value="{{ $key }}"
                class="form-check-input {{ $field['class'] }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
                {{ old($field['name'], $value ) == $key ? 'checked' : '' }}
            >
            <label class="form-check-label" for="{{ $field['id'].'_'.$key }}">{{ $option }}</label>
        </div>
    @endforeach
    @error($field['name']) <div class="invalid-feedback d-block">{{ $message ?? '' }}</div> @enderror
</div>
