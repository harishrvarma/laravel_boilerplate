<li>
    <label>
    <input type="checkbox" 
        name="menu[resource_ids][]" 
        value="{{ $node->id }}" 
        class="menu-checkbox"
        @checked(in_array($node->id, (array) ($selected ?? [])))>
        {{ $node->label ?? $node->name }}
    </label>

    @if ($node->children->isNotEmpty())
        <ul class="ml-4">
            @foreach($node->children as $child)
                {!! (new \Modules\Menu\View\Components\Menu\Listing\Edit\Tabs\Resource\RenderResource)
                        ->node($child)
                        ->selected($selected)
                        ->render() !!}
            @endforeach
        </ul>
    @endif
</li>
