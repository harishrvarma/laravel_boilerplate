@php 
    $column = $me->column();
    $value = $me->value($column['name']);
@endphp

<input type="hidden" 
       name="show_in_grid[{{ $row->entity_type_id }}][{{ $row->attribute_id }}]" 
       value="0">

<input type="checkbox" class="show_in_grid" 
       name="show_in_grid[{{ $row->entity_type_id }}][{{ $row->attribute_id }}]" 
       value="1" 
       @if(isset($row->show_in_grid) && $row->show_in_grid) checked @endif>
