@php
    $grid = $me->getGridBlock();   // get the grid block object
    $visibleColumns = implode(',', array_column($grid->columns(), 'name'));
@endphp

<form id="main-form" action="{{ urlx() }}" method="post">
    @csrf
    {{-- Hidden input for visible columns --}}
    <input type="hidden" name="visible_columns" id="visible_columns" value="{{ $visibleColumns }}">

    {{-- Top buttons --}}
    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 mb-3 p-2">
        @foreach ($me->buttons() as $button)
            <a href="{{ $button['route'] ?? '#' }}" 
               class="btn btn-primary btn-sm px-3 py-2 shadow-sm">
               {{ $button['label'] ?? '' }}
            </a>
        @endforeach
    </div>

    {{-- Grid --}}
    {{ $grid->render() }}
</form>
