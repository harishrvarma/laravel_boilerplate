@php
    $filter = $me->filter();
    $name = $filter['name'];
@endphp
<div class="filter-date mb-3">    
    <input
        type="date"
        id="filter-{{ $name }}"
        name="filter[{{ $name }}]"
        value="{{ request()->input("filter.$name") }}"
        class="border border-gray-300 px-2 py-1 w-full rounded focus:outline-none focus:ring focus:ring-indigo-200"
    >
</div>
