@php
    $filter = $me->filter();
    $name = $filter['name'];
@endphp
<div class="filter-range mb-3">
    <div class="flex gap-2">
        <input
            type="number"
            name="filter[{{ $name }}][min]"
            placeholder="Min"
            value="{{ request()->input("filter.$name.min") }}"
            class="border border-gray-300 px-2 py-1 w-1/2 rounded focus:outline-none focus:ring focus:ring-indigo-200"
        >
        <input
            type="number"
            name="filter[{{ $name }}][max]"
            placeholder="Max"
            value="{{ request()->input("filter.$name.max") }}"
            class="border border-gray-300 px-2 py-1 w-1/2 rounded focus:outline-none focus:ring focus:ring-indigo-200"
        >
    </div>
</div>
