<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">{{ $field['label'] ?? ucfirst($field['name']) }}</label>
    <input 
        type="file" 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}{{ !empty($field['multiple']) ? '[]' : '' }}" 
        class="form-control {{ $field['class'] }} {{ $errors->has($field['name']) ? 'is-invalid' : '' }}"
        {{ $field['multiple'] }}
        {{ $field['accept']) ? 'accept='.$field['accept'] : '' }}
    >
    @if(!empty($row) && !empty($row->{$field['name']}))
        <p class="mt-2">
            <a href="{{ asset('storage/'.$row->{$field['name']}) }}" target="_blank">Current File</a>
        </p>
    @endif
    @error($field['name']) <div class="invalid-feedback">{{ $message ?? '' }}</div> @enderror
</div>
