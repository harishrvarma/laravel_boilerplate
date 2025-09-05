<li>

    @if ($me->node()->children->isNotEmpty())
        <span class="toggle me-1" style="cursor:pointer;">[+]</span>
    @else
        <span class="me-3"></span>
    @endif

    <label>
    <input type="checkbox" name="resources[]" value="{{$me->node()->id}}" {{ (in_array($me->node()->id, $me->selected()) ? ' checked' : '') }} >
        {{$me->node()->label}}
    </label>

    @if ($me->node()->children->isNotEmpty()) 
    <ul class="ms-4 list-unstyled">
        @foreach ($me->node()->children as $child) 
            {{ $me->getResourceBlock($child, $me->selected())->render() }}
        @endforeach
        </ul>
    @endif

</li>