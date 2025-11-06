@php 
    $column = $me->column();
    $value = $me->value($column['name']);
@endphp

<input type="hidden" 
       name="default_in_grid[{{ $row->entity_type_id }}][{{ $row->attribute_id }}]" 
       value="0">

<input type="checkbox" class="default_in_grid" 
       name="default_in_grid[{{ $row->entity_type_id }}][{{ $row->attribute_id }}]" 
       value="1" 
       @if(isset($row->default_in_grid) && $row->default_in_grid) checked @endif>
