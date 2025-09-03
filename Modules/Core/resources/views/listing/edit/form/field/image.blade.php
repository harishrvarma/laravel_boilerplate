<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>
    <input 
        type="file" 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        class="form-control {{ $field['class'] }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
        accept="image/*"
        onchange="previewImage{{ $field['id'] }}(event)"
    >
    <div class="mt-2">
        <img id="preview-{{ $field['id'] }}" 
             src="{{ !empty($row->{$field['name']}) ? asset('storage/'.$row->{$field['name']}) : '' }}" 
             alt="Preview" 
             style="max-height: 120px; {{ empty($row->{$field['name']}) ? 'display:none;' : '' }}">
    </div>
    @error($field['name']) <div class="invalid-feedback">{{ $message ?? ''}}</div> @enderror
</div>

<script>
    function previewImage{{ $field['id'] }}(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('preview-{{ $field['id'] }}');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }
</script>
