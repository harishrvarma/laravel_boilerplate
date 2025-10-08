@php 
    $column = $me->column();
    $checked = isset($row->selected) && $row->selected ? 'checked' : '';
@endphp

<input type="checkbox" class="mass_ids" name="mass_ids[{{ $me->value($column['name']) }}]" value="{{ $me->value($column['name']) }}"{{$checked}}>
