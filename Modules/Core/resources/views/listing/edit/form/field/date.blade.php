@php
    use Illuminate\Support\Carbon;

    $raw = $field['value'] ?? null;

    // Normalize to Y-m-d for HTML date inputs
    if ($raw instanceof \DateTimeInterface) {
        $dbValue = $raw->format('Y-m-d');
    } elseif (is_string($raw) && !empty($raw)) {
        try { 
            $dbValue = Carbon::parse($raw)->format('Y-m-d'); 
        } catch (\Throwable $e) { 
            $dbValue = $raw; 
        }
    } else {
        $dbValue = '';
    }

    $currentValue = old($field['name'], $dbValue);

    $placeholder = $field['placeholder'] ?? '';
    $min = $field['min'] ?? '';
    $max = $field['max'] ?? '';
    $required = !empty($field['required']);
    $showToday = $field['todayBtn'] ?? true;
    $showClear = $field['clearBtn'] ?? true;
    $inputId = $field['id'] ?? $field['name'];
@endphp


<div class="mb-3">
    <label for="{{ $inputId }}" class="form-label">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>

    <div class="input-group">
        <span class="input-group-text">
            <i class="fas fa-calendar-alt"></i>
        </span>

        <input
            type="date"
            id="{{ $inputId }}"
            name="{{ $field['name'] }}"
            value="{{ $currentValue }}"
            class="form-control {{ $field['class'] ?? '' }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
            placeholder="{{ $placeholder }}"
            @if($min !== '') min="{{ $min }}" @endif
            @if($max !== '') max="{{ $max }}" @endif
            @if($required) required @endif
        >

        @if($showToday)
            <button type="button" class="btn btn-outline-secondary" onclick="setToday_{{ $inputId }}()">
                Today
            </button>
        @endif

        @if($showClear)
            <button type="button" class="btn btn-outline-secondary" onclick="clearDate_{{ $inputId }}()">
                <i class="fas fa-times"></i>
            </button>
        @endif
    </div>

    @error($field['name'])
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    @if(!empty($field['help']))
        <small class="form-text text-muted">{{ $field['help'] }}</small>
    @endif
</div>

@push('scripts')
<script>
function setToday_{{ $inputId }}() {
    const el = document.getElementById('{{ $inputId }}');
    const now = new Date();
    // Format yyyy-mm-dd in local time
    const yyyy = now.getFullYear();
    const mm = String(now.getMonth() + 1).padStart(2, '0');
    const dd = String(now.getDate()).padStart(2, '0');
    const today = `${yyyy}-${mm}-${dd}`;

    // Respect min/max if set
    const min = el.getAttribute('min');
    const max = el.getAttribute('max');
    let val = today;
    if (min && val < min) val = min;
    if (max && val > max) val = max;

    el.value = val;
    el.dispatchEvent(new Event('change'));
}

function clearDate_{{ $inputId }}() {
    const el = document.getElementById('{{ $inputId }}');
    el.value = '';
    el.dispatchEvent(new Event('change'));
}
</script>
@endpush
