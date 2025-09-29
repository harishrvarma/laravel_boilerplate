@php
    $value = $field['id'] ?? '';
    $value = $row->$value ?? null;
@endphp
<div class="form-group {{ $field['class'] }}" id="options-wrapper">
    <label for="{{ $field['id'] }}" class="form-label">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>

    <textarea 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        class="form-control @error($field['name']) is-invalid @enderror"
        rows="{{ $field['rows'] ?? 4 }}"
        placeholder="{{ $field['placeholder'] ?? '' }}"
    >{{ old($field['name'], $value ?? '') }}
    </textarea>
    @if (!empty($field['note']))
        <small class="form-text text-muted">{{ $field['note'] }}</small>
    @endif
    @error($field['name'])
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
