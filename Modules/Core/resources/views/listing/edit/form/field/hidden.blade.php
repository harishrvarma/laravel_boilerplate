@php
    $valueKey = $field['id'] ?? $field['name'] ?? null;
    $dbValue  = $valueKey && $row ? ($row->$valueKey ?? null) : null;
    $value = old($field['name'], $dbValue ?? ($field['value'] ?? ''));
@endphp

<input 
    type="hidden" 
    id="{{ $field['id'] ?? $field['name'] }}" 
    name="{{ $field['name'] ?? '' }}" 
    value="{{ $value }}"
>
