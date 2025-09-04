{{-- Loop Fields --}}
@if($fields = $me->fields())
    @foreach($fields as $key => $field)
        @php
            echo $me->getFieldBlock($field)->render();
        @endphp
    @endforeach
@endif

@if($buttons = $me->buttons())
    <div class="card-footer d-flex justify-content-end gap-2">
        @foreach($buttons as $button)
            @if(isset($button['method']))
                <button type="button" id="{{ $button['id'] }}" class="{{ $button['class'] }}" onclick="{{ $button['method'] }}">
                    {{ $button['name'] }}
                </button>
            @else
                <button type="{{ $button['type'] ?? 'submit' }}" id="{{ $button['id'] }}" class="{{ $button['class'] }}">
                    {{ $button['name'] }}
                </button>
            @endif
        @endforeach
    </div>
@endif
