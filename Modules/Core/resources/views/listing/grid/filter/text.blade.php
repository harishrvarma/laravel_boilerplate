@php
    $filter = $me->filter();
    $name = $filter['name'];
    $value = request()->input("filter.$name", '');
    $placeholder = $filter['placeholder'] ?? 'Search...';
@endphp

<div class="mb-3">
    <div class="input-group">
        <input
            type="text"
            id="filter-{{ $name }}"
            name="filter[{{ $name }}]"
            value="{{ $value }}"
            class="form-control"
            placeholder="{{ $placeholder }}"
        >
    </div>
</div>
