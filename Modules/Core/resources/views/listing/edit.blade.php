<div class="card shadow-sm">
    <div class="card-header d-flex align-items-center">
            <h5 class="mb-0">{{ $me->title() }}</h5>
    </div>

<form action="{{ $me->saveUrl() }}" method="post">
    @csrf
    {{ $me->getTabsBlock()->render() }}

    <div class="mt-3">
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
</form>
