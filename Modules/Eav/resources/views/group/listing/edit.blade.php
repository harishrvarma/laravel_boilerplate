<div class="d-flex justify-content-end align-items-center flex-wrap gap-2 mb-3 p-2"></div>

<div class="col-sm-6">
    <h2 class="mb-3">{{ $me->title() }}</h2>
</div>

<div class="card shadow-sm">
    <form action="{{ $me->saveUrl() }}" method="post">
        @csrf

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
        
        {{-- Tabs block --}}
        @if($tabs = $me->getTabsBlock())
            <div class="card-body">
                {{ $tabs->render() }}
            </div>
        @endif
    </form>
</div>
