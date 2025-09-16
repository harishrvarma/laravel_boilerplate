@php
    $valueKey = $field['id'] ?? '';
    $relation = $field['relation'] ?? null;

    if ($relation && method_exists($row, $relation)) {
        $selectedValues = $row->{$relation}()->pluck($valueKey)->toArray() ?? [];
    } elseif (!empty($field['selected'])) {
        $selectedValues = is_array($field['selected']) ? $field['selected'] : [$field['selected']];
    } else {
        $selectedValues = $row->$valueKey ? [$row->$valueKey] : [];
    }
@endphp


<div class="mb-3">
    <label for="{{ $field['id'] }}" class="form-label">
        {{ $field['label'] ?? ucfirst($field['name']) }}
    </label>

    <select
        id="{{ $field['id'] }}"
        name="{{ $field['name'] }}"
        class="form-select select2 {{ $field['class'] ?? '' }}"
        @if(!empty($field['multiselect'])) multiple @endif
        data-placeholder="{{ $field['placeholder'] ?? 'Select option(s)' }}"
    >
    @foreach(($field['options'] ?? []) as $key => $option)
    <option value="{{ $key }}"
        {{ in_array($key, $selectedValues) ? 'selected' : '' }}>
        {{ $option }}
    </option>
@endforeach

    </select>

    @error($field['name'])
        <div class="invalid-feedback">{{ $message ?? '' }}</div>
    @enderror
</div>

@push('scripts')
<script>
$(function () {
    $('#{{ $field['id'] }}').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: function() {
            return $(this).data('placeholder') || 'Select option(s)';
        },
        allowClear: true,
        tags: {{ !empty($field['tags']) ? 'true' : 'false' }},
        templateSelection: function(data) {
            if (!data.id) return data.text;
            return $('<span style="background-color:#007bff;color:white;padding:3px 8px;border-radius:10px;font-size:0.9em;margin-right:2px;">'+data.text+'</span>');
        },
        templateResult: function(data) {
            return data.text;
        }
    });

    let $inputType    = $("#input_type");
    let $optionsField = $(".settings-options");

    function toggleOptions() {
        let needsOptions = ["select", "radio", "checkbox"];
        if (needsOptions.includes($inputType.val())) {
            $optionsField.removeClass("d-none");
        } else {
            $optionsField.addClass("d-none");
        }
    }

    toggleOptions();
    $inputType.on("change", toggleOptions);
});

</script>
@endpush
