@php
    $filter = $me->filter();
    $name = $filter['name'];
    $value = request()->input("filter.$name", '');
@endphp

<div class="mb-3">
    <div class="input-group">
        <select
            id="filter-{{ $name }}"
            name="filter[{{ $name }}]"
            class="form-select"
        >
            <option value="">-- Select --</option>
            @foreach($filter['options'] ?? [] as $key => $optionLabel)
                <option value="{{ $key }}" {{ $value == $key ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    </div>
</div>
