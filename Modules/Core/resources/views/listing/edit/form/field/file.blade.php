@php
    $valueKey = $field['id'] ?? '';
    $currentFiles = $field['value'] ?? null;
    $multiple = !empty($field['multiple']);
    $accept = !empty($field['accept']) ? 'accept="'.$field['accept'].'"' : '';
@endphp

<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>

    <div class="input-group">
        <span class="input-group-text">
            <i class="fas fa-file-alt"></i>
        </span>
        <input 
            type="file"
            id="{{ $field['id'] }}"
            name="{{ $field['name'] }}{{ $multiple ? '[]' : '' }}"
            class="form-control {{ $field['class'] ?? '' }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
            {{ $multiple ? 'multiple' : '' }}
            {!! $accept !!}
            onchange="previewFile{{ $field['id'] }}(event)"
        >
        <button type="button" class="btn btn-outline-secondary" onclick="clearFile{{ $field['id'] }}()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <div class="mt-2" id="preview-container-{{ $field['id'] }}">
        @if(!empty($currentFiles))
            @php
                $files = is_array($currentFiles) ? $currentFiles : [$currentFiles];
            @endphp
            @foreach($files as $file)
                <p>
                    <i class="fas fa-file"></i> 
                    <a href="{{ asset('storage/'.$file) }}" target="_blank">{{ basename($file) }}</a>
                </p>
            @endforeach
        @endif
    </div>

    @error($field['name'])
        <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror
</div>

@push('scripts')
<script>
function previewFile{{ $field['id'] }}(event) {
    const files = event.target.files;
    const container = document.getElementById('preview-container-{{ $field['id'] }}');
    container.innerHTML = '';
    Array.from(files).forEach(file => {
        const p = document.createElement('p');
        const a = document.createElement('a');
        a.href = URL.createObjectURL(file);
        a.target = '_blank';
        a.textContent = file.name;
        p.appendChild(a);
        container.appendChild(p);
    });
}

function clearFile{{ $field['id'] }}() {
    const input = document.getElementById('{{ $field['id'] }}');
    const container = document.getElementById('preview-container-{{ $field['id'] }}');
    input.value = '';
    container.innerHTML = '';
}
</script>
@endpush
