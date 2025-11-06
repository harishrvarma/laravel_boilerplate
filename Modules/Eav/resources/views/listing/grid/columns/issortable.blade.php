@php 
    $column = $me->column();
    $value = $me->value($column['name']);
@endphp

<input type="hidden" 
       name="is_sortable[{{ $row->entity_type_id }}][{{ $row->attribute_id }}]" 
       value="0">

<input type="checkbox" class="is_sortable" 
       name="is_sortable[{{ $row->entity_type_id }}][{{ $row->attribute_id }}]" 
       value="1" 
       @if(isset($row->is_sortable) && $row->is_sortable) checked @endif>
