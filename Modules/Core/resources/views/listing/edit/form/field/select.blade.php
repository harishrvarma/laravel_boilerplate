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

{{-- Options builder section (hidden by default) --}}
<div class="mb-3 settings-options d-none">
    <label class="form-label">Options</label>

    {{-- Option rows --}}
    <div id="options-wrapper">
        <div class="option-row d-flex gap-2 mb-2">
            <input type="text" name="options[0][key]" class="form-control w-25" placeholder="Key">
            <input type="text" name="options[0][value]" class="form-control w-50" placeholder="Value">
            <button type="button" class="btn btn-danger btn-remove-option">Remove</button>
        </div>
    </div>
    <button type="button" id="add-option" class="btn btn-primary mt-2">Add More</button>

    {{-- Textarea for function-based options --}}
    <div class="mt-3">
        <label class="form-label">Dynamic Options Source (optional)</label>
        <textarea name="options_source" id="options_source" class="form-control" rows="2"
                  placeholder="Enter function path e.g. Modules\Settings\Models\ConfigOption::options"></textarea>
        <small class="text-muted">If this field is filled, manual options above will be ignored.</small>
    </div>
</div>


@push('scripts')
<script>
$(function () {
    // Initialize Select2
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

    // Dynamic option fields
    let optionIndex = 1;

    $('#add-option').on('click', function () {
        let newRow = `
            <div class="option-row d-flex gap-2 mb-2">
                <input type="text" name="options[${optionIndex}][key]" class="form-control w-25" placeholder="Key">
                <input type="text" name="options[${optionIndex}][value]" class="form-control w-50" placeholder="Value">
                <button type="button" class="btn btn-danger btn-remove-option">Remove</button>
            </div>
        `;
        $('#options-wrapper').append(newRow);
        optionIndex++;
    });

    // Remove option row
    $(document).on('click', '.btn-remove-option', function () {
        $(this).closest('.option-row').remove();
    });
});
</script>
@endpush
