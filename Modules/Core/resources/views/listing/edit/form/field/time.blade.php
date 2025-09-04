@php
    use Illuminate\Support\Carbon;

    $valueKey = $field['id'] ?? $field['name'] ?? null;
    $raw = $valueKey && isset($row) ? ($row->$valueKey ?? null) : null;

    // Normalize to H:i for HTML5 time
    if ($raw instanceof \DateTimeInterface) {
        $dbValue = $raw->format('H:i');
    } elseif (is_string($raw) && !empty($raw)) {
        try { $dbValue = Carbon::parse($raw)->format('H:i'); } catch (\Throwable $e) { $dbValue = $raw; }
    } else {
        $dbValue = '';
    }

    $currentValue = old($field['name'], $dbValue);

    $placeholder = $field['placeholder'] ?? '';
    $min = $field['min'] ?? '';
    $max = $field['max'] ?? '';
    $step = $field['step'] ?? '60'; // default: 1-minute steps
    $required = !empty($field['required']);
    $inputId = $field['id'] ?? $field['name'];
@endphp

<div class="mb-3">
    <label for="{{ $inputId }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>

    <div class="input-group">
        <span class="input-group-text"><i class="fas fa-hourglass-half"></i></span>
        <input
            type="time"
            id="{{ $inputId }}"
            name="{{ $field['name'] }}"
            value="{{ $currentValue }}"
            class="form-control {{ $field['class'] ?? '' }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
            placeholder="{{ $placeholder }}"
            step="{{ $step }}"
            @if($min !== '') min="{{ $min }}" @endif
            @if($max !== '') max="{{ $max }}" @endif
            @if($required) required @endif
        >
    </div>

    @error($field['name'])
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>
