@php
    $filter = $me->filter();
    $name = $filter['name'];
@endphp
<div class="filter-textarea mb-3">
    <textarea
        id="filter-{{ $name }}"
        name="filter[{{ $name }}]"
        class="border border-gray-300 px-2 py-1 w-full rounded focus:outline-none focus:ring focus:ring-indigo-200"
    >{{ request()->input("filter.$name") }}</textarea>
</div>
