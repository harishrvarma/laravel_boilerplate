@php 
$column = $me->column();
@endphp
{{ $me->value($column['name']) }}