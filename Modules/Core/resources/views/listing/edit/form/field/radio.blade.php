@php
    $valueKey = $field['id'] ?? '';
    $currentValue = $row->$valueKey ?? null;
    $inline = $field['inline'] ?? true;
    $switch = $field['switch'] ?? false;
@endphp

<div class="mb-3">
    <label class="form-label d-block">{{ $field['label'] ?? ucfirst($field['name']) }}</label>

    @foreach(($field['options'] ?? []) as $key => $option)
        @php
            $radioId = $field['id'].'_'.$key;
            $isChecked = old($field['name'], $currentValue) == $key;
            $formCheckClass = 'form-check';
            if ($inline) $formCheckClass .= ' form-check-inline';
            if ($switch) $formCheckClass .= ' form-switch';
        @endphp

        <div class="{{ $formCheckClass }}">
            <input 
                type="radio"
                id="{{ $radioId }}"
                name="{{ $field['name'] }}"
                value="{{ $key }}"
                class="form-check-input {{ $field['class'] ?? '' }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
                {{ $isChecked ? 'checked' : '' }}
            >
            <label class="form-check-label" for="{{ $radioId }}">
                {{ $option }}
            </label>
        </div>
    @endforeach

    @error($field['name'])
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    @if(!empty($field['help']))
        <small class="form-text text-muted">{{ $field['help'] }}</small>
    @endif
</div>
