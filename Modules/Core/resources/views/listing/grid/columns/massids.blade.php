@php 
    $column = $me->column();
@endphp

<input type="checkbox" class="mass_ids" name="mass_ids[{{ $me->value($column['name']) }}]" value="{{ $me->value($column['name']) }}">
