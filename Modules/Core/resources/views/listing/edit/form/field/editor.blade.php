@php
    $value = $field['id'] ?? '';
    $value = $row->$value ?? null;
@endphp

<div class="form-group {{ $field['class'] }}">
    <label for="{{ $field['id'] }}" class="form-label">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>

    <textarea 
        id="{{ $field['id'] }}" 
        name="{{ $field['name'] }}" 
        class="form-control summernote {{ $field['class'] ?? '' }} @error($field['name']) is-invalid @enderror"
        rows="{{ $field['rows'] ?? 6 }}"
        placeholder="{{ $field['placeholder'] ?? '' }}"
        {{ !empty($field['required']) ? 'required' : '' }}
    >{{ old($field['name'], $value ?? ($field['value'] ?? '')) }}</textarea>

    @error($field['name'])
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
@push('scripts')
    <script>
    $(function () {
        $('.summernote').summernote({
            height: 200,
            dialogsInBody: true,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onInit: function() {
                    $('body').on('click', '.note-modal .close', function() {
                        $(this).closest('.note-modal').modal('hide');
                    });
                }
            }
        });

    });
    </script>
@endpush