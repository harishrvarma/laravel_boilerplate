<form id='main-form' action="{{ urlx() }}" method="post">
    @csrf
    <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 mb-3 p-2">
        @foreach ($me->buttons() as $button)
            <a href="{{ $button['route'] ?? '#' }}" 
            class="btn btn-primary btn-sm px-3 py-2 shadow-sm">
            {{ $button['label'] ?? '' }}
            </a>
        @endforeach
    </div>
    {{ $me->getGridBlock()->render() }}
</form>