<li data-id="{{ $me->node()->id }}">
    <div class="form-check">
        <input class="form-check-input menu-checkbox"
               type="checkbox"
               data-id="{{ $me->node()->id }}"
               data-parent="{{ $me->node()->parent_id ?? '' }}"
               {{ $me->node()->is_active ? 'checked' : '' }}>
        <label class="form-check-label">
            {{ $me->node()->title }}
        </label>
    </div>

    @if ($me->node()->children->isNotEmpty())
        <ul class="ms-4 list-unstyled">
            @foreach ($me->node()->children as $child)
                {{ $me->tree()->getMenuBlock($child)->render() }}
            @endforeach
        </ul>
    @endif
</li>
