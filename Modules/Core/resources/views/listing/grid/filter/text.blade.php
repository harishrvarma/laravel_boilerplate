@php
    $filter = $me->filter();
    $name = $filter['name'];
@endphp
<div class="filter-text mb-3">

    <input
        type="text"
        id="filter-{{ $filter['name'] }}"
        name="filter[{{ $filter['name'] }}]"
        value="{{ request()->input("filter.$name") }}"
        class="border border-gray-300 px-2 py-1 w-full rounded focus:outline-none focus:ring focus:ring-indigo-200"
    >
</div>
