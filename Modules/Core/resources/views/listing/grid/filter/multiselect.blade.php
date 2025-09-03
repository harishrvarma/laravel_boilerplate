@php
    $filter = $me->filter();
    $name = $filter['name'];
@endphp
<div class="filter-multiselect mb-3">
    <select
        id="filter-{{ $name }}"
        name="filter[{{ $name }}][]"
        multiple
        class="border border-gray-300 px-2 py-1 w-full rounded focus:outline-none focus:ring focus:ring-indigo-200"
    >
        @foreach($filter['options'] ?? [] as $key => $optionLabel)
            <option value="{{ $key }}"
                {{ in_array($key, (array) request()->input("filter.$name", [])) ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
