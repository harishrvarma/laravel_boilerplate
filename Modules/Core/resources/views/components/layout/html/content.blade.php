@foreach($me->children() as $key => $child)
    {{ $child->render() }}
@endforeach