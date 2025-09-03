@php
    $filter = $me->filter();
    $name = $filter['name'];
@endphp
<div class="filter-boolean mb-3">

    <select
        id="filter-{{ $name }}"
        name="filter[{{ $name }}]"
        class="border border-gray-300 px-2 py-1 w-full rounded focus:outline-none focus:ring focus:ring-indigo-200"
    >
        <option value="">-- Select --</option>
        <option value="1" {{ request()->input("filter.$name") === '1' ? 'selected' : '' }}>Yes</option>
        <option value="0" {{ request()->input("filter.$name") === '0' ? 'selected' : '' }}>No</option>
    </select>
</div>
