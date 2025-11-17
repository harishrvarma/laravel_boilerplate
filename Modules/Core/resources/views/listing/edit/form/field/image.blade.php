@php
    $currentValue = $field['value'] ?? null;
    $imagePath = $currentValue ? asset('storage/' . $currentValue) : null;
@endphp

<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>

    <div class="input-group">
        <span class="input-group-text">
            <i class="fas fa-image"></i>
        </span>
        <input 
            type="file" 
            id="{{ $field['id'] }}" 
            name="{{ $field['name'] }}" 
            class="form-control {{ $field['class'] ?? '' }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}" 
            accept="image/*"
            onchange="previewImage{{ $field['id'] }}(event)"
        >
        <button type="button" class="btn btn-outline-secondary" onclick="clearImage{{ $field['id'] }}()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="mt-2" id="preview-container-{{ $field['id'] }}">
        @if($imagePath)
            <img 
                id="preview-{{ $field['id'] }}" 
                src="{{ $imagePath }}" 
                class="img-thumbnail" 
                style="max-height: 120px;">
        @else
            <img 
                id="preview-{{ $field['id'] }}" 
                src="{{ asset('dist/img/placeholder.png') }}" 
                class="img-thumbnail" 
                style="max-height: 120px; display:none;">
        @endif
    </div>

    @error($field['name'])
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
function previewImage{{ $field['id'] }}(event) {
    const [file] = event.target.files;
    const preview = document.getElementById('preview-{{ $field['id'] }}');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}

function clearImage{{ $field['id'] }}() {
    const input = document.getElementById('{{ $field['id'] }}');
    const preview = document.getElementById('preview-{{ $field['id'] }}');
    input.value = '';
    preview.src = '{{ asset('dist/img/placeholder.png') }}';
    preview.style.display = 'none';
}
</script>
@endpush
