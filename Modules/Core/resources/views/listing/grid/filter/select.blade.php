@php
    $filter = $me->filter();
    $name = $filter['name'];
@endphp
<div class="filter-select mb-3">
    <select
        id="filter-{{ $name }}"
        name="filters[{{ $name }}]"
        class="border border-gray-300 px-2 py-1 w-full rounded focus:outline-none focus:ring focus:ring-indigo-200"
    >
        <option value="">-- Select --</option>
        @foreach($filter['options'] ?? [] as $key => $optionLabel)
            <option value="{{ $key }}" {{ request()->input("filter.$name") == $key ? 'selected' : '' }}>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
</div>
